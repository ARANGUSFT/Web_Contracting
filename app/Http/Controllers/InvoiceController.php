<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\CompanyLocation;
use App\Models\Item;
use App\Models\InvoicePayoutItem;
use App\Models\User;
use App\Models\Crew;
use App\Models\JobRequest;
use App\Models\Emergencies;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * ============================
     * LISTADO DE FACTURAS
     * ============================
     */
    public function index(Request $request)
    {
        $query = Invoice::query()
            ->with(['companyLocation', 'companyLocation.user', 'payoutItems', 'invoiceable'])
            ->withSum('items as invoice_subtotal', 'total');

        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }
        if ($request->filled('company_id')) {
            $query->whereHas('companyLocation.user', fn($q) =>
                $q->where('id', $request->company_id));
        }
        if ($request->filled('state')) {
            $query->whereHas('companyLocation', fn($q) =>
                $q->where('state', $request->state));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('period')) {
            $now = now();
            switch ($request->period) {
                case 'this_month':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->startOfMonth()->toDateString(),
                        $now->copy()->endOfMonth()->toDateString()
                    ]); break;
                case 'last_30_days':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subDays(30)->toDateString(),
                        $now->toDateString()
                    ]); break;
                case 'this_quarter':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->startOfQuarter()->toDateString(),
                        $now->copy()->endOfQuarter()->toDateString()
                    ]); break;
                case 'this_year':
                    $query->whereYear('invoice_date', $now->year); break;
            }
        }

        $invoices = $query->orderBy('invoice_date', 'desc')
            ->paginate(20)
            ->withQueryString();

        $companies = User::whereNotNull('company_name')
            ->whereHas('companyLocations')
            ->orderBy('company_name')
            ->get();

        $states = CompanyLocation::select('state')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');

        return view('admin.invoices.index', compact('invoices', 'companies', 'states'));
    }

    /**
     * ============================
     * FORMULARIO DE CREACIÓN
     * ============================
     */
   public function create()
{
    $companies = User::whereNotNull('company_name')->get();
    $crews     = Crew::where('is_active', true)->get();

    // Busca el siguiente número que no exista en la tabla
    $base = 1000;
    do {
        $base++;
        $candidate = 'INV-' . $base;
    } while (Invoice::where('invoice_number', $candidate)->exists());

    $nextInvoiceNumber = $candidate;

    return view('admin.invoices.create', compact('companies', 'nextInvoiceNumber', 'crews'));
}

    /**
     * ============================
     * ITEMS POR UBICACIÓN
     * ============================
     */
    public function itemsByLocation($locationId)
    {
        $location = CompanyLocation::find($locationId);

        if (!$location) {
            return response()->json([], 404);
        }

        $items = Item::with('category')
            ->leftJoin('item_prices', function ($join) use ($location) {
                $join->on('items.id', '=', 'item_prices.item_id')
                    ->where('item_prices.company_location_id', $location->id)
                    ->where('item_prices.is_active', true);
            })
            ->select(
                'items.id',
                'items.name',
                'items.category_id',
                DB::raw('COALESCE(item_prices.price, items.global_price, 0) as price')
            )
            ->where('items.is_active', true)
            ->orderBy('items.sort_order')
            ->orderBy('items.name')
            ->get()
            ->map(fn($item) => [
                'id'       => $item->id,
                'name'     => $item->name,
                'price'    => $item->price,
                'category' => $item->category?->name ?? 'Uncategorized',
            ]);

        return response()->json($items);
    }

    /**
     * ============================
     * GUARDAR FACTURA
     * ============================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_location_id' => 'required|exists:company_locations,id',
            'invoice_number'      => 'required|string|max:50|unique:invoices,invoice_number',
            'crew_id'             => 'nullable|exists:crews,id',
            'invoice_date'        => 'required|date',
            'due_date'            => 'nullable|date',
            'customer_email'      => 'nullable|email',
            'address'             => 'nullable|string|max:255',
            'bill_to'             => 'nullable|string|max:255',
            'status'              => 'nullable|in:draft,sent,paid',
            'tax'                 => 'nullable|numeric|min:0',
            'items'               => 'required|array|min:1',
            'items.*.id'          => 'nullable|integer',
            'items.*.name'        => 'required|string',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.note'        => 'nullable|string|max:2000',
            'memo'                => 'nullable|string',
            'notes'               => 'nullable|string',
            'attachments.*'       => 'file|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $invoice = Invoice::create([
                'user_id'             => auth()->id(),
                'company_location_id' => $validated['company_location_id'],
                'crew_id'             => $validated['crew_id'] ?? null,
                'customer_email'      => $validated['customer_email'] ?? null,
                'bill_to'             => $validated['bill_to'] ?? null,
                'address'             => $validated['address'] ?? null,
                'invoice_number'      => $validated['invoice_number'],
                'invoice_date'        => $validated['invoice_date'],
                'due_date'            => $validated['due_date'] ?? null,
                'status'              => $validated['status'] ?? 'draft',
                'memo'                => $validated['memo'] ?? null,
                'notes'               => $validated['notes'] ?? null,
                'subtotal'            => 0,
                'tax'                 => $validated['tax'] ?? 0,
                'total'               => 0,
            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['price'] * $item['quantity'];
                $subtotal += $lineTotal;

                $invoice->items()->create([
                    'item_id'     => !empty($item['id']) ? $item['id'] : null,
                    'description' => $item['name'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'note'        => $item['note'] ?? null,
                    'total'       => $lineTotal,
                ]);
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + ($invoice->tax ?? 0),
            ]);

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

            // ── Asociar a job / emergency / repair ───────────────
            $typeMap = [
                'job'       => \App\Models\JobRequest::class,
                'emergency' => \App\Models\Emergencies::class,
                'repair'    => \App\Models\RepairTicket::class,
            ];
            $invType = $request->input('invoiceable_type');
            $invId   = $request->input('invoiceable_id');
            if ($invType && $invId && isset($typeMap[$invType])) {
                $invoice->invoiceable_type = $typeMap[$invType];
                $invoice->invoiceable_id   = (int) $invId;
                $invoice->save();
            }

            DB::commit();

            return response()->json([
                'success'    => true,
                'invoice_id' => $invoice->id,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * ============================
     * FORMULARIO DE EDICIÓN
     * ============================
     */
    public function edit(Invoice $invoice)
    {
        $companies = User::whereNotNull('company_name')->get();
        $crews     = Crew::where('is_active', true)->get();

        $invoiceItems = $invoice->items->map(fn($i) => [
            'id'       => $i->item_id,
            'name'     => $i->description,
            'price'    => (float) $i->price,
            'quantity' => (int)   $i->quantity,
            'note'     => $i->note,
        ]);

        return view('admin.invoices.edit', compact('invoice', 'companies', 'invoiceItems', 'crews'));
    }

    /**
     * ============================
     * ACTUALIZAR FACTURA
     * ============================
     */
    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'company_location_id' => 'required|exists:company_locations,id',
            'crew_id'             => 'nullable|exists:crews,id',
            'invoice_date'        => 'required|date',
            'due_date'            => 'nullable|date',
            'customer_email'      => 'nullable|email',
            'bill_to'             => 'nullable|string|max:255',
            'address'             => 'nullable|string|max:255',
            'status'              => 'required|in:draft,sent,paid',
            'items'               => 'required|array|min:1',
            'items.*.id'          => 'nullable|integer',
            'items.*.name'        => 'required|string',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.quantity'    => 'required|integer|min:1',
            'items.*.note'        => 'nullable|string|max:2000',
            'memo'                => 'nullable|string',
            'notes'               => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $invoice->update([
                'company_location_id' => $validated['company_location_id'],
                'crew_id'             => $validated['crew_id'] ?? null,
                'customer_email'      => $validated['customer_email'] ?? null,
                'bill_to'             => $validated['bill_to'] ?? null,
                'address'             => $validated['address'] ?? null,
                'invoice_date'        => $validated['invoice_date'],
                'due_date'            => $validated['due_date'] ?? null,
                'status'              => $validated['status'],
                'memo'                => $validated['memo'] ?? null,
                'notes'               => $validated['notes'] ?? null,
            ]);

            $invoice->items()->delete();

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = (float) $item['price'] * (int) $item['quantity'];
                $subtotal += $lineTotal;

                $invoice->items()->create([
                    'item_id'     => !empty($item['id']) ? $item['id'] : null,
                    'description' => $item['name'],
                    'price'       => (float) $item['price'],
                    'quantity'    => (int)   $item['quantity'],
                    'note'        => $item['note'] ?? null,
                    'total'       => $lineTotal,
                ]);
            }

            $tax = (float) ($invoice->tax ?? 0);

            $invoice->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + $tax,
            ]);

            DB::commit();

            return response()->json([
                'success'    => true,
                'invoice_id' => $invoice->id,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ============================
     * VER FACTURA
     * ============================
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['items', 'companyLocation.user']);
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * ============================
     * ELIMINAR FACTURA
     * ============================
     */
    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return redirect()->back()->with('success', 'Invoice deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }

    /**
     * ============================
     * DESCARGAR PDF
     * ============================
     */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['items', 'companyLocation', 'attachments']);

        $pdf = Pdf::loadView('admin.invoices.pdf_invoices', compact('invoice'))
            ->setPaper('a4');

        return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
    }

    /**
     * ============================
     * PREPARAR PAYOUT
     * ============================
     */
    public function prepareInvoice(Invoice $invoice)
    {
        $invoice->load(['items.item', 'crew', 'payoutItems']);

        DB::transaction(function () use ($invoice) {
            foreach ($invoice->items as $invoiceItem) {
                $existingPayout = $invoice->payoutItems()
                    ->where('description', $invoiceItem->description)
                    ->first();

                if (!$existingPayout) {
                    $price = 0;

                    if ($invoiceItem->item && $invoice->crew) {
                        $price = $invoiceItem->item->getCrewPrice(
                            $invoice->crew->has_trailer
                        );
                    }

                    $invoice->payoutItems()->create([
                        'description' => $invoiceItem->description,
                        'quantity'    => $invoiceItem->quantity,
                        'price'       => $price,
                        'total'       => $price * $invoiceItem->quantity,
                    ]);
                }
            }
        });

        $availableItems = Item::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $invoice->load('payoutItems');

        return view('admin.invoices.prepare_payout', compact('invoice', 'availableItems'));
    }

    /**
     * ============================
     * GENERAR PDF CUSTOM (PAYOUT)
     * ============================
     */
    public function generateCustomPdf(Request $request, Invoice $invoice)
    {
        $request->validate([
            'address'             => 'nullable|string|max:255',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.quantity'    => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request, $invoice) {
            $invoice->update([
                'address' => $request->input('address', $invoice->address),
            ]);

            $invoice->payoutItems()->delete();

            foreach ($request->items as $item) {
                $invoice->payoutItems()->create([
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'quantity'    => $item['quantity'],
                    'total'       => $item['price'] * $item['quantity'],
                ]);
            }
        });

        $invoice->load('payoutItems', 'crew');

        $pdf = \PDF::loadView('admin.invoices.pdf_payout_custom', [
            'invoice' => $invoice,
            'items'   => $invoice->payoutItems,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("Payout-{$invoice->invoice_number}-Payout.pdf");
    }



   public function linked(Request $request)
    {
        $typeMap = [
            'job'       => \App\Models\JobRequest::class,
            'emergency' => \App\Models\Emergencies::class,
            'repair'    => \App\Models\RepairTicket::class,
        ];

        $type = $request->query('type');
        $id   = $request->query('id');

        if (!$type || !$id || !isset($typeMap[$type])) {
            return response()->json(null);
        }

        $invoice = \App\Models\Invoice::where('invoiceable_type', $typeMap[$type])
            ->where('invoiceable_id', $id)
            ->latest()
            ->first(['id', 'invoice_number']);

        return response()->json($invoice); // null si no existe, {id, invoice_number} si existe
    }

    public function workOrderInfo(Request $request)
    {
        $type = $request->query('type');
        $id   = $request->query('id');

        if ($type === 'job') {
            $record = \App\Models\JobRequest::find($id);
            $userId = $record?->user_id;
        } elseif ($type === 'emergency') {
            $record = \App\Models\Emergencies::find($id);
            $userId = $record?->user_id;
        } elseif ($type === 'repair') {
            $record = \App\Models\RepairTicket::find($id);
            if ($record?->reference_type === 'job') {
                $userId = \App\Models\JobRequest::find($record->reference_id)?->user_id;
            } else {
                $userId = \App\Models\Emergencies::find($record->reference_id)?->user_id;
            }
        } else {
            return response()->json(null);
        }

        return response()->json(['company_id' => $userId]);
    }


}