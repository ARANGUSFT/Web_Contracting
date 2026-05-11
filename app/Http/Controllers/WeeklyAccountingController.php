<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Emergencies;
use App\Models\Invoice;
use App\Models\JobRequest;
use App\Models\RepairTicket;
use App\Models\WeeklyAccountingCost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeeklyAccountingController extends Controller
{
    /**
     * Items que cuentan como SQ (Squares).
     * Se compara case-insensitive con el `description` de cada invoice_item.
     */
    protected const SQ_ITEM_NAMES = [
        'flat roof r&r (sa base & cap) [sq]',
        'asphalt shingles install only (shingle, starter & ridge) [sq]',
        'metal roof r&r [sq]',
        'top per [sq]',
    ];

    // ══════════════════════════════════════════════════════════════
    // PUBLIC ENDPOINTS
    // ══════════════════════════════════════════════════════════════

    /**
     * Main view — shows paginated weeks with invoices aggregated per week.
     */
    public function index(Request $request)
    {
        $page         = (int) $request->query('page', 1);
        $weeksPerPage = 5;

        $invoicesData = $this->loadInvoicesWithDates();

        if ($invoicesData->isEmpty()) {
            return view('admin.weekly-accounting.index', [
                'weeks'           => collect(),
                'page'            => 1,
                'currentPage'     => 1,
                'totalPages'      => 1,
                'totalWeeks'      => 0,
                'equityPct'       => AppSetting::getValue('equity_percentage', 4.00),
                'weekStartDay'    => AppSetting::getValue('week_start_day', 2),
                'allPeriodsData'  => ['invoices' => []],
                'currentWeekKey'  => null,
                'indexTree'       => collect(),
                'activeYear'      => (string) Carbon::now()->year,
                'activeMonth'     => Carbon::now()->format('Y-m'),
            ]);
        }

        $weekStartDay = (int) AppSetting::getValue('week_start_day', 2);
        $weeksMap     = $this->groupByWeek($invoicesData, $weekStartDay);

        // Cargar semanas que solo tienen costos (sin invoices)
        $costsOnly = WeeklyAccountingCost::whereNotIn(
                'week_start',
                $weeksMap->keys()->all()
            )
            ->orderBy('week_start', 'desc')
            ->get();

        foreach ($costsOnly as $c) {
            $key = $c->week_start->toDateString();
            if (!$weeksMap->has($key)) {
                $weeksMap->put($key, [
                    'week_start' => $c->week_start,
                    'week_end'   => $c->week_end,
                    'pay_date'   => $c->week_start->copy()->addDays(10),
                    'invoices'   => collect(),
                ]);
            }
        }

        // Asignar Index cronológico a cada semana
        $weeksAsc     = $weeksMap->sortBy(fn($w) => $w['week_start']->timestamp)->values();
        $weekIndexMap = [];
        foreach ($weeksAsc as $i => $w) {
            $weekIndexMap[$w['week_start']->toDateString()] = $i + 1;
        }

        // Ordenar descendente para mostrar
        $sortedWeeks = $weeksMap->sortByDesc(fn($w) => $w['week_start']->timestamp)->values();

        $totalWeeks = $sortedWeeks->count();
        $totalPages = max(1, (int) ceil($totalWeeks / $weeksPerPage));
        $page       = min($page, $totalPages);

        $paginatedWeeks = $sortedWeeks
            ->slice(($page - 1) * $weeksPerPage, $weeksPerPage)
            ->values();

        $costsByWeek = WeeklyAccountingCost::whereIn('week_start',
            $paginatedWeeks->pluck('week_start')->map(fn($d) => $d->toDateString())->all()
        )->get()->keyBy(fn($c) => $c->week_start->toDateString());

        $equityPct = (float) AppSetting::getValue('equity_percentage', 4.00);
        $weeks     = $paginatedWeeks->map(function ($w) use ($costsByWeek, $equityPct, $weekIndexMap) {
            $enriched = $this->enrichWeekData($w, $costsByWeek, $equityPct);
            $enriched['week_index'] = $weekIndexMap[$enriched['week_key']] ?? null;
            return $enriched;
        });

        // Data para el dashboard global (KPIs + chart)
        $allPeriodsData = [
            'invoices' => $invoicesData->map(fn($i) => [
                'id'         => $i->id,
                'date'       => $i->date->toDateString(),
                'invoiced'   => $i->invoiced,
                'payout'     => $i->payout,
                'address'    => $i->address,
                'job_label'  => $i->job_label,
                'quickbooks' => $i->quickbooks,
            ])->values()->all(),
        ];

        $today          = Carbon::now();
        $currentRange   = $this->getWeekRange($today, $weekStartDay);
        $currentWeekKey = $currentRange['start']->toDateString();

        // Construir índice tipo libro contable: Años → Meses (por pay_date)
        $indexTree = $sortedWeeks
            ->groupBy(fn($w) => $w['pay_date']->format('Y'))
            ->map(function ($yearWeeks) {
                return $yearWeeks
                    ->groupBy(fn($w) => $w['pay_date']->format('Y-m'))
                    ->map(fn($monthWeeks) => [
                        'month_key'   => $monthWeeks->first()['pay_date']->format('Y-m'),
                        'month_label' => $monthWeeks->first()['pay_date']->format('F Y'),
                        'count'       => $monthWeeks->count(),
                    ])
                    ->values();
            });

        // Mes activo por defecto = pay_date de hoy
        $todayPayDate = $today->copy()->addDays(10);
        $activeYear   = (string) $todayPayDate->year;
        $activeMonth  = $todayPayDate->format('Y-m');

        if ($indexTree->isNotEmpty() && !$indexTree->has($activeYear)) {
            $activeYear = (string) $indexTree->keys()->first();
        }
        if ($indexTree->has($activeYear)) {
            $monthsForYear = $indexTree->get($activeYear);
            $hasMonth = $monthsForYear->contains(fn($m) => $m['month_key'] === $activeMonth);
            if (!$hasMonth) {
                $activeMonth = $monthsForYear->first()['month_key'];
            }
        }

        return view('admin.weekly-accounting.index', [
            'weeks'           => $weeks,
            'page'            => $page,
            'currentPage'     => $page,
            'totalPages'      => $totalPages,
            'totalWeeks'      => $totalWeeks,
            'equityPct'       => $equityPct,
            'weekStartDay'    => $weekStartDay,
            'allPeriodsData'  => $allPeriodsData,
            'currentWeekKey'  => $currentWeekKey,
            'indexTree'       => $indexTree,
            'activeYear'      => $activeYear,
            'activeMonth'     => $activeMonth,
        ]);
    }

    public function saveCosts(Request $request)
    {
        $data = $request->validate([
            'week_start'     => 'required|date',
            'week_end'       => 'required|date|after:week_start',
            'landfill'       => 'nullable|numeric|min:0',
            'fuel'           => 'nullable|numeric|min:0',
            'other'          => 'nullable|numeric|min:0',
            'driver'         => 'nullable|numeric|min:0',
            'superintendent' => 'nullable|numeric|min:0',
            'ceo'            => 'nullable|numeric|min:0',
            'notes'          => 'nullable|string|max:2000',
        ]);

        try {
            $existing = WeeklyAccountingCost::where('week_start', $data['week_start'])
                ->where('week_end', $data['week_end'])
                ->first();

            $payload = [
                'landfill'       => $data['landfill']       ?? 0,
                'fuel'           => $data['fuel']           ?? 0,
                'other'          => $data['other']          ?? 0,
                'driver'         => $data['driver']         ?? 0,
                'superintendent' => $data['superintendent'] ?? 0,
                'ceo'            => $data['ceo']            ?? 0,
                'notes'          => $data['notes']          ?? null,
                'updated_by'     => auth()->id(),
            ];

            if ($existing) {
                $existing->update($payload);
                $cost = $existing;
            } else {
                $payload['week_start'] = $data['week_start'];
                $payload['week_end']   = $data['week_end'];
                $payload['created_by'] = auth()->id();
                $cost = WeeklyAccountingCost::create($payload);
            }

            return response()->json([
                'success' => true,
                'message' => 'Operating costs saved successfully.',
                'data'    => $cost,
            ]);
        } catch (\Throwable $e) {
            \Log::error('WeeklyAccounting saveCosts error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error saving: '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateSettings(Request $request)
    {
        $data = $request->validate([
            'equity_percentage' => 'required|numeric|min:0|max:100',
        ]);

        AppSetting::setValue('equity_percentage', $data['equity_percentage']);

        return response()->json(['success' => true, 'value' => $data['equity_percentage']]);
    }

    public function togglePaid(Request $request)
    {
        $data = $request->validate([
            'invoice_id' => 'required|integer|exists:invoices,id',
            'paid'       => 'required|boolean',
        ]);

        try {
            $invoice = Invoice::findOrFail($data['invoice_id']);
            $invoice->subcontractor_paid    = (bool) $data['paid'];
            $invoice->subcontractor_paid_at = $data['paid'] ? now() : null;
            $invoice->save();

            return response()->json([
                'success' => true,
                'paid'    => (bool) $invoice->subcontractor_paid,
                'paid_at' => $invoice->subcontractor_paid_at?->format('m/d/Y H:i'),
            ]);
        } catch (\Throwable $e) {
            \Log::error('WeeklyAccounting togglePaid error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════════════════════════

    /**
     * Load all invoices with their effective work date and computed SQ.
     */
    protected function loadInvoicesWithDates()
    {
        $invoices = Invoice::with([
                'invoiceable',
                'payoutItems',
                'items',           // ⭐ NEW — Para calcular SQ
                'crew.subcontractors',
            ])
            ->whereNotNull('invoiceable_type')
            ->whereNotNull('invoiceable_id')
            ->orderBy('invoice_date', 'desc')
            ->get();

        return $invoices->map(function ($inv) {
            $rel = $inv->invoiceable;
            if (!$rel) return null;

            $date = match ($inv->invoiceable_type) {
                JobRequest::class   => $rel->install_date_requested ?? $inv->invoice_date,
                Emergencies::class  => $rel->date_submitted         ?? $inv->invoice_date,
                RepairTicket::class => $rel->repair_date            ?? $inv->invoice_date,
                default             => $inv->invoice_date,
            };

            if (!$date) return null;
            $workDate = Carbon::parse($date);

            $payoutTotal = $inv->payoutItems->sum(fn($i) => $i->price * $i->quantity);

            $jobLabel = match ($inv->invoiceable_type) {
                JobRequest::class   => $rel->job_number_name ?? '#'.$rel->id,
                Emergencies::class  => $rel->job_number_name ?? '#'.$rel->id,
                RepairTicket::class => 'RT-'.str_pad($rel->id, 4, '0', STR_PAD_LEFT),
                default             => 'INV-'.$inv->invoice_number,
            };

            $jobType = match ($inv->invoiceable_type) {
                JobRequest::class   => 'job',
                Emergencies::class  => 'emergency',
                RepairTicket::class => 'repair',
                default             => 'other',
            };

            $companyName = match ($inv->invoiceable_type) {
                JobRequest::class   => $rel->company_name ?? '—',
                Emergencies::class  => $rel->company_name ?? '—',
                RepairTicket::class => (function() use ($rel) {
                    if ($rel->reference_type === 'job') {
                        return optional($rel->jobRequest)->company_name ?? 'Repair';
                    }
                    return optional($rel->emergency)->company_name ?? 'Repair';
                })(),
                default             => '—',
            };

            $address = match ($inv->invoiceable_type) {
                JobRequest::class   => trim(collect([
                    $rel->job_address_street_address,
                    $rel->job_address_city,
                    $rel->job_address_state,
                    $rel->job_address_zip_code,
                ])->filter()->implode(', ')),
                Emergencies::class  => trim(collect([
                    $rel->job_address,
                    $rel->job_city,
                    $rel->job_state,
                    $rel->job_zip_code,
                ])->filter()->implode(', ')),
                RepairTicket::class => '—',
                default             => '—',
            };

            // ────────────────────────────────────────────────
            //  CREW NAME + SUBCONTRACTOR NAME
            // ────────────────────────────────────────────────
            $crewName = '—';
            $subName  = '—';

            if ($inv->crew) {
                $crewName = $inv->crew->name ?? '—';

                $firstSub = $inv->crew->subcontractors->first();
                if ($firstSub) {
                    $subName = $firstSub->name ?? '—';
                }
            }

            if ($crewName === '—' && $subName === '—' && $inv->payoutItems->count() > 0) {
                $subName = $inv->payoutItems->first()->description ?? '—';
            }

            // ────────────────────────────────────────────────
            //  ⭐ SQ — sumar quantities de items SQ
            // ────────────────────────────────────────────────
            $sq = $this->calculateSq($inv);

            return (object) [
                'id'                    => $inv->id,
                'number'                => $inv->invoice_number,
                'quickbooks'            => $inv->invoice_number,
                'status'                => $inv->status,
                'date'                  => $workDate,
                'invoiced'              => (float) $inv->total,
                'subtotal'              => (float) $inv->subtotal,
                'payout'                => (float) $payoutTotal,
                'job_label'             => $jobLabel,
                'job_type'              => $jobType,
                'job_id'                => $inv->invoiceable_id,
                'company_name'          => $companyName,
                'address'               => $address,
                'crew_name'             => $crewName,
                'subcontractor'         => $subName,
                'sq'                    => $sq,
                'subcontractor_paid'    => (bool) $inv->subcontractor_paid,
                'subcontractor_paid_at' => $inv->subcontractor_paid_at,
            ];
        })->filter()->values();
    }

    /**
     * Calculate SQ (Squares) for an invoice by summing the quantities
     * of items whose description matches one of the SQ items.
     *
     * Returns null if no SQ items are present (so the table shows "—").
     */
    protected function calculateSq(Invoice $inv): ?float
    {
        if ($inv->items->isEmpty()) {
            return null;
        }

        $total = $inv->items
            ->filter(function ($item) {
                $desc = strtolower(trim($item->description ?? ''));
                return in_array($desc, self::SQ_ITEM_NAMES, true);
            })
            ->sum('quantity');

        return $total > 0 ? (float) $total : null;
    }

    /**
     * Group invoices into weeks based on the week start day.
     */
    protected function groupByWeek($invoices, int $weekStartDay)
    {
        $map = collect();

        foreach ($invoices as $inv) {
            $range = $this->getWeekRange($inv->date, $weekStartDay);
            $key   = $range['start']->toDateString();

            if (!$map->has($key)) {
                $map->put($key, [
                    'week_start' => $range['start'],
                    'week_end'   => $range['end'],
                    'pay_date'   => $range['pay_date'],
                    'invoices'   => collect(),
                ]);
            }

            $existing = $map->get($key);
            $existing['invoices']->push($inv);
            $map->put($key, $existing);
        }

        return $map;
    }

    /**
     * Return [start, end, pay_date] of the accounting week containing $date.
     *
     * Tuesday → Monday work week. Pay date = Tuesday + 10 days = Friday next week.
     */
    protected function getWeekRange(Carbon $date, int $weekStartDay): array
    {
        $diff    = ($date->dayOfWeek - $weekStartDay + 7) % 7;
        $start   = $date->copy()->subDays($diff)->startOfDay();
        $end     = $start->copy()->addDays(6)->endOfDay();
        $payDate = $start->copy()->addDays(10)->startOfDay();

        return ['start' => $start, 'end' => $end, 'pay_date' => $payDate];
    }

    /**
     * Add computed fields + operating costs to a week record.
     */
    protected function enrichWeekData(array $week, $costsByWeek, float $equityPct): array
    {
        $key  = $week['week_start']->toDateString();
        $cost = $costsByWeek->get($key);

        $landfill       = $cost->landfill       ?? 0;
        $fuel           = $cost->fuel           ?? 0;
        $other          = $cost->other          ?? 0;
        $driver         = $cost->driver         ?? 0;
        $superintendent = $cost->superintendent ?? 0;
        $ceo            = $cost->ceo            ?? 0;
        $notes          = $cost->notes          ?? '';

        $opsTotal = (float) ($landfill + $fuel + $other + $driver + $superintendent + $ceo);

        $invoices = $week['invoices'];
        $invoiced = (float) $invoices->sum('invoiced');
        $subPaid  = (float) $invoices->sum('payout');
        $totalSq  = (float) $invoices->sum('sq');  // suma todos los SQ de los invoices de la semana

        $payout            = $subPaid + $opsTotal;
        $grossBeforeEquity = $invoiced - $payout;
        $equity            = $grossBeforeEquity * ($equityPct / 100);
        $gross             = $grossBeforeEquity - $equity;
        $margin            = $invoiced > 0 ? ($gross / $invoiced) * 100 : 0;

        return [
            'week_start'     => $week['week_start'],
            'week_end'       => $week['week_end'],
            'pay_date'       => $week['pay_date'] ?? $week['week_start']->copy()->addDays(10),
            'week_key'       => $key,
            'invoices'       => $invoices,
            'invoices_count' => $invoices->count(),

            'costs' => [
                'landfill'       => (float) $landfill,
                'fuel'           => (float) $fuel,
                'other'          => (float) $other,
                'driver'         => (float) $driver,
                'superintendent' => (float) $superintendent,
                'ceo'            => (float) $ceo,
                'notes'          => $notes,
                'ops_total'      => $opsTotal,
            ],

            'totals' => [
                'invoiced'            => $invoiced,
                'sub_paid'            => $subPaid,
                'payout'              => $payout,
                'gross_before_equity' => $grossBeforeEquity,
                'gross'               => $gross,
                'margin'              => $margin,
                'equity'              => $equity,
                'sq'                  => $totalSq,
            ],
        ];
    }
}