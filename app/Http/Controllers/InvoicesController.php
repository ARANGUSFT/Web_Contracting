<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Invoices;


class InvoicesController extends Controller
{
    
   public function index(Request $request)
    {
        $q       = trim((string) $request->query('q'));
        $perPage = (int) $request->query('perPage', 25);

        $base = Calendar::query()
            ->with(['invoice', 'jobRequest.crew', 'emergency.crew'])
            ->whereIn('type', ['job','emergency']);

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

        $page = $base->orderByDesc('start')->paginate($perPage)->withQueryString();

        $invoices = collect($page->items())->map(function ($cal) {
            if ($cal->type === 'job' && $cal->jobRequest) {
                $crew    = optional($cal->jobRequest->crew)->name;
                $address = $cal->jobRequest->job_address_street_address;
            } elseif ($cal->type === 'emergency' && $cal->emergency) {
                $crew    = optional($cal->emergency->crew)->name;
                $address = $cal->emergency->job_address;
            } else {
                $crew = null; $address = null;
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

        $totalsQuery = Calendar::query()
            ->leftJoin('invoices', 'invoices.calendar_id', '=', 'calendars.id')
            ->whereIn('calendars.type', ['job','emergency']);

        if ($q !== '') {
            $like = '%' . $q . '%';
            $totalsQuery->where(function ($w) use ($like) {
                $w->where('calendars.title', 'like', $like)
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

        $totals = [
            'paid' => (float) ($totalsQuery->clone()->selectRaw('COALESCE(SUM(invoices.paid),0) as s')->value('s') ?? 0),
            'due'  => (float) ($totalsQuery->clone()->selectRaw('COALESCE(SUM(invoices.due),0)  as s')->value('s') ?? 0),
        ];

        return view('admin.invoices.inv', [
            'invoices' => $invoices,
            'totals'   => $totals,
            'page'     => $page,       // para links()
            'q'        => $q,          // por si quieres repintar el input
            'perPage'  => $perPage,    // por si agregas selector de tamaño
        ]);
    }



    public function open(int $calendarId)
    {
        $invoice = \App\Models\Invoices::firstOrCreate(['calendar_id' => $calendarId], ['paid' => 0, 'due' => 0]);
        return redirect()->route('superadmin.invoices.payments.index', $invoice);
    }


    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'calendar_id' => ['required','integer','exists:calendars,id'],
            'total'       => ['required','numeric','min:0'], // 👈 solo total desde esta vista
        ]);

        $invoice = \App\Models\Invoices::firstOrNew(['calendar_id' => $data['calendar_id']]);

        $paid  = (float)($invoice->paid ?? 0);         // lo que ya está pagado
        $total = (float)$data['total'];                // total ingresado
        $due   = max(0, $total - $paid);               // faltante

        $invoice->paid = $paid;
        $invoice->due  = $due;
        $invoice->save();

        return back()->with('status', 'Total actualizado. Faltante recalculado.');
    }

}


