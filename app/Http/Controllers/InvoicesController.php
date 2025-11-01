<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Invoices;

class InvoicesController extends Controller
{
    public function index(Request $request)
    {
        // Filtros
        $q       = trim((string) $request->query('q'));
        $perPage = (int) $request->query('perPage', 25);
        $perPage = $perPage > 0 ? min($perPage, 100) : 25; // límite sano

        // Base con relaciones necesarias
        $base = Calendar::query()
            ->with(['invoice', 'jobRequest.crew', 'emergency.crew'])
            ->whereIn('type', ['job','emergency']);

        // Búsqueda
        if ($q !== '') {
            $like = '%' . $q . '%';
            $base->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                  ->orWhereHas('jobRequest', function ($q1) use ($like) {
                      $q1->where('job_address_street_address', 'like', $like)
                         ->orWhereHas('crew', fn ($qc) => $qc->where('name', 'like', $like));
                  })
                  ->orWhereHas('emergency', function ($q2) use ($like) {
                      $q2->where('job_address', 'like', $like)
                         ->orWhereHas('crew', fn ($qc) => $qc->where('name', 'like', $like));
                  });
            });
        }

        // Paginación + transformación sin perder el paginador
        $invoices = $base->orderByDesc('start')
            ->paginate($perPage)
            ->withQueryString()
            ->through(function ($cal) {
                if ($cal->type === 'job' && $cal->jobRequest) {
                    $crew    = optional($cal->jobRequest->crew)->name;
                    $address = $cal->jobRequest->job_address_street_address;
                } elseif ($cal->type === 'emergency' && $cal->emergency) {
                    $crew    = optional($cal->emergency->crew)->name;
                    $address = $cal->emergency->job_address;
                } else {
                    $crew = null; 
                    $address = null;
                }

                return [
                    'calendar_id'  => $cal->id,
                    'job_number'   => $cal->title,
                    'crew_project' => $crew,
                    'job_address'  => $address,
                    'paid'         => (float)($cal->invoice->paid ?? 0),
                    'due'          => (float)($cal->invoice->due ?? 0),
                ];
            });

        // Totales (misma lógica de filtros, usando relaciones ya cargadas)
        $totalsBase = Calendar::query()
            ->with('invoice')
            ->whereIn('type', ['job','emergency']);

        if ($q !== '') {
            $like = '%' . $q . '%';
            $totalsBase->where(function ($w) use ($like) {
                $w->where('title', 'like', $like)
                  ->orWhereHas('jobRequest', function ($q1) use ($like) {
                      $q1->where('job_address_street_address', 'like', $like)
                         ->orWhereHas('crew', fn ($qc) => $qc->where('name', 'like', $like));
                  })
                  ->orWhereHas('emergency', function ($q2) use ($like) {
                      $q2->where('job_address', 'like', $like)
                         ->orWhereHas('crew', fn ($qc) => $qc->where('name', 'like', $like));
                  });
            });
        }

        // Sumatorias en PHP (simple y seguro con relaciones)
        $totalsCollection = $totalsBase->get();
        $totals = [
            'paid' => (float) $totalsCollection->sum(fn ($c) => (float)($c->invoice->paid ?? 0)),
            'due'  => (float) $totalsCollection->sum(fn ($c) => (float)($c->invoice->due  ?? 0)),
        ];

        return view('admin.invoices.inv', [
            'invoices' => $invoices,  // paginator transformado -> puedes usar firstItem/lastItem/links
            'totals'   => $totals,
            'q'        => $q,
            'perPage'  => $perPage,
        ]);
    }

    public function open(int $calendarId)
    {
        $invoice = Invoices::firstOrCreate(
            ['calendar_id' => $calendarId],
            ['paid' => 0, 'due' => 0]
        );

        return redirect()->route('superadmin.invoices.payments.index', $invoice);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'calendar_id' => ['required','integer','exists:calendars,id'],
            'total'       => ['required', 'string'], // <- string para poder sanitizar “5.000,50”
        ]);

        $invoice = \App\Models\Invoices::firstOrNew(['calendar_id' => $data['calendar_id']]);

        $total = $this->toDecimal($data['total']);     // ⬅️ normaliza “5.000,50” => 5000.50 (float)
        $paid  = (float)($invoice->paid ?? 0);
        $due   = max(0, $total - $paid);

        $invoice->paid = $paid;
        $invoice->due  = $due;
        $invoice->save();

        return back()->with('status', 'Updated total. Missing recalculated.');
    }

    /**
     * Normaliza valores monetarios escritos con miles y decimales en distintos formatos.
     * Ejemplos:
     *  "5.000"     -> 5000
     *  "5,000"     -> 5000
     *  "5.000,50"  -> 5000.50
     *  "5,000.50"  -> 5000.50
     */
    private function toDecimal(string $value): float
    {
        $v = preg_replace('/[^\d,.\-]/', '', $value); // deja dígitos/coma/punto/-
        if ($v === '' || $v === null) return 0.0;

        $hasComma = str_contains($v, ',');
        $hasDot   = str_contains($v, '.');

        if ($hasComma && $hasDot) {
            // Si tiene ambos, asumimos que el último símbolo es el separador decimal
            $lastComma = strrpos($v, ',');
            $lastDot   = strrpos($v, '.');
            if ($lastComma > $lastDot) {
                // “5.000,50” => quita puntos, cambia coma por punto
                $v = str_replace('.', '', $v);
                $v = str_replace(',', '.', $v);
            } else {
                // “5,000.50” => quita comas (miles), deja punto decimal
                $v = str_replace(',', '', $v);
            }
        } elseif ($hasComma) {
            // Solo coma: asumimos coma como decimal -> cambia por punto
            // Pero si hay más de una coma, probablemente son miles: quítalas
            if (substr_count($v, ',') === 1) {
                $v = str_replace(',', '.', $v);
            } else {
                $v = str_replace(',', '', $v);
            }
        } else {
            // Solo puntos: si hay más de uno, probablemente miles -> quítalos
            if (substr_count($v, '.') > 1) {
                $v = str_replace('.', '', $v);
            }
            // con un solo punto lo dejamos como decimal
        }

        return (float)$v;
    }

}
