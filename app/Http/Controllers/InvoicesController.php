<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\Invoices;


class InvoicesController extends Controller
{
    
    public function index()
    {
        $calendars = Calendar::with(['invoice','jobRequest.crew','emergency.crew'])
            ->whereIn('type', ['job','emergency'])
            ->orderByDesc('start')
            ->get();

        $invoices = $calendars->map(function ($cal) {
            if ($cal->type === 'job' && $cal->jobRequest) {
                $crew    = $cal->jobRequest->crew?->name;
                $address = $cal->jobRequest->job_address_street_address;
            } elseif ($cal->type === 'emergency' && $cal->emergency) {
                $crew    = $cal->emergency->crew?->name;
                $address = $cal->emergency->job_address;
            } else {
                $crew = null; $address = null;
            }

            return [
                'calendar_id'  => $cal->id,                // 👈 clave necesaria para el form
                'job_number'   => $cal->title,
                'crew_project' => $crew,
                'job_address'  => $address,
                'paid'         => (float)($cal->invoice->paid ?? 0),
                'due'          => (float)($cal->invoice->due ?? 0),
            ];
        })->values(); // reindexa por si acaso

        $totals = [
            'paid' => $invoices->sum('paid'),
            'due'  => $invoices->sum('due'),
        ];

        return view('admin.invoices.inv', compact('invoices','totals'));
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


