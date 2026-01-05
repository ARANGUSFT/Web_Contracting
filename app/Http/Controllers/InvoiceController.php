<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\CompanyLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class InvoiceController extends Controller
{
    /**
     * LISTADO DE FACTURAS
     */
   public function index(Request $request)
    {
            $query = Invoice::with(['companyLocation', 'user']);

            // 🔍 Invoice #
            if ($request->filled('invoice_number')) {
                $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
            }

            // 🏢 Company
            if ($request->filled('company_id')) {
                $query->where('user_id', $request->company_id);
            }

            // 📍 State
            if ($request->filled('state')) {
                $query->whereHas('companyLocation', function ($q) use ($request) {
                    $q->where('state', $request->state);
                });
            }

            // 🏷️ Status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // 📆 Period (QuickBooks style)
        if ($request->filled('period')) {

            $now = now();

            switch ($request->period) {

                case 'this_month':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->startOfMonth(),
                        $now->copy()->endOfMonth()
                    ]);
                    break;

                case 'last_3_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(3)->startOfDay(),
                        $now
                    ]);
                    break;

                case 'last_6_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(6)->startOfDay(),
                        $now
                    ]);
                    break;

                case 'last_12_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(12)->startOfDay(),
                        $now
                    ]);
                    break;

                case 'this_year':
                    $query->whereYear('invoice_date', $now->year);
                    break;
            }
        }

            $invoices = $query->latest()->paginate(20)->withQueryString();

            // Para los filtros
            $companies = \App\Models\User::whereNotNull('company_name')->get();
            $states = \App\Models\CompanyLocation::select('state')->distinct()->orderBy('state')->pluck('state');

            return view('admin.invoices.index', compact(
                'invoices',
                'companies',
                'states'
            ));
    }



    /**
     * FORMULARIO DE CREACIÓN
     */
    public function create()
    {
        $companies = \App\Models\User::whereNotNull('company_name')->get();
        return view('admin.invoices.create', compact('companies'));
    }

    /**
     * GUARDAR FACTURA
     */
   public function store(Request $request)
{
    // 1️⃣ VALIDATION
    $validated = $request->validate([
        'company_location_id' => 'required|exists:company_locations,id',
        'invoice_date'        => 'required|date',
        'due_date'            => 'nullable|date',
        'customer_email'      => 'nullable|email',
        'bill_to'             => 'nullable|string|max:255',
        'status'              => 'nullable|in:draft,sent,paid',
        'tax'                 => 'nullable|numeric|min:0',

        'items'               => 'required|array|min:1',
        'items.*.id'          => 'required|integer',
        'items.*.name'        => 'required|string',
        'items.*.price'       => 'required|numeric|min:0',
        'items.*.quantity'    => 'required|integer|min:1',
        'invoice_number' => 'required|string|max:50|unique:invoices,invoice_number',

        // Opcionales (ya los tienes en DB)
        'memo'                => 'nullable|string',
        'notes'               => 'nullable|string',

        // Attachments (opcional)
        'attachments.*'       => 'file|max:10240', // 10MB
    ]);

    DB::beginTransaction();

    try {

        // 2️⃣ CREATE INVOICE (BASE)
        $invoice = Invoice::create([
            'user_id'             => auth()->id(),
            'company_location_id' => $validated['company_location_id'],
            'customer_email'      => $validated['customer_email'] ?? null,
            'bill_to'             => $validated['bill_to'] ?? null,
            'invoice_number' => $validated['invoice_number'],
            'invoice_date'        => $validated['invoice_date'],
            'due_date'            => $validated['due_date'] ?? null,
            'status'              => $validated['status'] ?? 'draft',
            'memo'                => $validated['memo'] ?? null,
            'notes'               => $validated['notes'] ?? null,
            'subtotal'            => 0,
            'tax'                 => $validated['tax'] ?? 0,
            'total'               => 0,
        ]);

        // 3️⃣ SAVE ITEMS + CALCULATE SUBTOTAL
        $subtotal = 0;

        foreach ($validated['items'] as $item) {

            $lineTotal = $item['price'] * $item['quantity'];
            $subtotal += $lineTotal;

            $invoice->items()->create([
                'item_id'     => $item['id'],
                'description' => $item['name'],
                'price'       => $item['price'],
                'quantity'    => $item['quantity'],
                'total'       => $lineTotal,
            ]);
        }

        // 4️⃣ UPDATE TOTALS
        $invoice->update([
            'subtotal' => $subtotal,
            'total'    => $subtotal + ($invoice->tax ?? 0),
        ]);

        // 5️⃣ ATTACHMENTS (OPTIONAL)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {

                $path = $file->store('invoice_attachments', 'public');

                $invoice->attachments()->create([
                    'original_name' => $file->getClientOriginalName(),
                    'file_path'     => $path,
                    'mime_type'     => $file->getMimeType(),
                    'size'          => $file->getSize(),
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success'    => true,
            'invoice_id' => $invoice->id,
        ]);

    } catch (\Throwable $e) {

        DB::rollBack();

        return response()->json([
            'error'   => true,
            'message' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * VER FACTURA
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'companyLocation']);
        return view('admin.invoices.show', compact('invoice'));
    }



 public function edit(Invoice $invoice)
{
    $companies = User::whereNotNull('company_name')->get();

    $invoiceItems = $invoice->items->map(function ($i) {
        return [
            'id'       => $i->item_id,
            'name'     => $i->description,
            'price'    => $i->price,
            'quantity' => $i->quantity,
        ];
    });

    return view('admin.invoices.edit', compact(
        'invoice',
        'companies',
        'invoiceItems'
    ));
}


    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'company_location_id' => 'required|exists:company_locations,id',
            'invoice_date'        => 'required|date',
            'items'               => 'required|array|min:1',
            'items.*.id'          => 'required|integer',
            'items.*.price'       => 'required|numeric',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {

            // UPDATE INVOICE
            $invoice->update([
                'company_location_id' => $request->company_location_id,
                'customer_email'      => $request->customer_email,
                'bill_to'             => $request->bill_to,
                'invoice_date'        => $request->invoice_date,
                'due_date'            => $request->due_date,
                'status'              => $request->status,
                'tax'                 => $request->tax ?? 0,
            ]);

            // 🔥 RESET ITEMS
            $invoice->items()->delete();

            $subtotal = 0;

            foreach ($request->items as $item) {
                $lineTotal = $item['price'] * $item['quantity'];

                $invoice->items()->create([
                    'item_id'     => $item['id'],
                    'description' => $item['name'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'total'       => $lineTotal,
                ]);

                $subtotal += $lineTotal;
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + ($request->tax ?? 0),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'invoice_id' => $invoice->id
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
