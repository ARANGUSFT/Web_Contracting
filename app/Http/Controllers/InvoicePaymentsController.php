<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoicePaymentsController extends Controller
{
    public function index(\App\Models\Invoices $invoice)
    {
        // ❌ quitar 'invoice' del load (no existe en el modelo Invoices)
        $invoice->load([
            'calendar.jobRequest.crew',
            'calendar.emergency.crew',
            'payments',
        ]);

        $jobNumber = $invoice->calendar->title;
        $crew = $address = null;

        if ($invoice->calendar->type === 'job' && $invoice->calendar->jobRequest) {
            $crew    = $invoice->calendar->jobRequest->crew?->name;
            $address = $invoice->calendar->jobRequest->job_address_street_address;
        } elseif ($invoice->calendar->type === 'emergency' && $invoice->calendar->emergency) {
            $crew    = $invoice->calendar->emergency->crew?->name;
            $address = $invoice->calendar->emergency->job_address;
        }

        return view('admin.invoices.payments', compact('invoice','jobNumber','crew','address'));
    }

    public function store(Request $request, Invoices $invoice)
    {
        $data = $request->validate([
            'amount'           => ['required','numeric','min:0.01'],
            'paid_at'          => ['required','date'],
            'method'           => ['nullable','string','max:100'],
            'reference'        => ['nullable','string','max:100'],
            'note'             => ['nullable','string','max:1000'],
            'attachments.*'    => ['file','max:5120'], // 5MB c/u
        ]);

        $files = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $files[] = $file->store('payments','public');
            }
        }

        DB::transaction(function () use ($invoice, $data, $files) {
            // 1) Registrar el pago
            InvoicePayment::create([
                'invoice_id' => $invoice->id,
                'amount'     => $data['amount'],
                'paid_at'    => $data['paid_at'],
                'method'     => $data['method'] ?? null,
                'reference'  => $data['reference'] ?? null,
                'note'       => $data['note'] ?? null,
                'attachments'=> $files ?: null,
                'created_by' => auth()->id(),
            ]);

            // 2) Actualizar totales en invoices (campo denormalizado)
            $newPaid = (float)$invoice->paid + (float)$data['amount'];
            $newDue  = max(0, (float)$invoice->due - (float)$data['amount']);
            $invoice->update(['paid' => $newPaid, 'due' => $newDue]);
        });

        return back()->with('status', 'Pago registrado.');
    }


    public function update(Request $request, \App\Models\Invoices $invoice, \App\Models\InvoicePayment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) abort(404);

        $data = $request->validate([
            'amount'            => ['required','numeric','min:0.01'],
            'paid_at'           => ['required','date'],
            'method'            => ['nullable','string','max:100'],
            'reference'         => ['nullable','string','max:100'],
            'note'              => ['nullable','string','max:1000'],
            'attachments.*'     => ['file','max:5120'], // 5MB c/u
            'remove_attachments'=> ['array'],
            'remove_attachments.*' => ['string'],
        ]);

        $oldAmount = (float) $payment->amount;
        $newAmount = (float) $data['amount'];
        $delta = $newAmount - $oldAmount;

        // Adjuntos existentes
        $current = $payment->attachments ?? [];
        if (!is_array($current)) $current = [];

        // Eliminar adjuntos marcados
        $toRemove = $data['remove_attachments'] ?? [];
        if (!empty($toRemove)) {
            foreach ($toRemove as $path) {
                if (in_array($path, $current, true) && \Storage::disk('public')->exists($path)) {
                    \Storage::disk('public')->delete($path);
                }
            }
            $current = array_values(array_diff($current, $toRemove));
        }

        // Agregar nuevos adjuntos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $current[] = $file->store('payments','public');
            }
        }

        \DB::transaction(function () use ($invoice, $payment, $data, $delta, $current) {
            // Actualizar pago
            $payment->update([
                'amount'      => $data['amount'],
                'paid_at'     => $data['paid_at'],
                'method'      => $data['method'] ?? null,
                'reference'   => $data['reference'] ?? null,
                'note'        => $data['note'] ?? null,
                'attachments' => $current,
            ]);

            // Ajustar totales del invoice (denormalizado)
            $invoice->update([
                'paid' => max(0, (float)$invoice->paid + $delta),
                'due'  => max(0, (float)$invoice->due  - $delta),
            ]);
        });

        return back()->with('status','Payment updated.');
    }

    public function download($invoiceId, $paymentId, $index)
    {
        $payment = InvoicePayment::where('invoice_id', $invoiceId)->findOrFail($paymentId);
        $attachments = $payment->attachments ?? [];

        if (!isset($attachments[$index])) {
            abort(404);
        }

        $path = storage_path('app/public/' . $attachments[$index]);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function destroy(Invoices $invoice, InvoicePayment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) abort(404);

        DB::transaction(function () use ($invoice, $payment) {
            // revertir totales
            $invoice->update([
                'paid' => max(0, (float)$invoice->paid - (float)$payment->amount),
                'due'  => (float)$invoice->due + (float)$payment->amount,
            ]);

            // borrar archivos
            $files = $payment->attachments ?? [];
            if (is_array($files)) {
                foreach ($files as $p) {
                    if ($p && Storage::disk('public')->exists($p)) {
                        Storage::disk('public')->delete($p);
                    }
                }
            }

            $payment->delete();
        });

        return back()->with('status', 'Pago eliminado.');
    }
}
