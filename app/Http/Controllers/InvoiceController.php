<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\CompanyLocation;
use App\Models\Item;
use App\Models\InvoicePayoutItem;

use App\Models\User;
use App\Models\Crew;
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
        $query = Invoice::with(['companyLocation', 'companyLocation.user', 'payoutItems']);

        // 🔍 Invoice #
        if ($request->filled('invoice_number')) {
            $query->where('invoice_number', 'like', '%' . $request->invoice_number . '%');
        }

        // 🏢 Company - Corregido: usar relación correcta
        if ($request->filled('company_id')) {
            $query->whereHas('companyLocation.user', function ($q) use ($request) {
                $q->where('id', $request->company_id);
            });
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

        // 📆 Period - Corregido: usar Carbon correctamente
        if ($request->filled('period')) {
            $now = now();

            switch ($request->period) {
                case 'this_month':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->startOfMonth()->toDateString(),
                        $now->copy()->endOfMonth()->toDateString()
                    ]);
                    break;

                case 'last_3_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(3)->startOfDay()->toDateString(),
                        $now->toDateString()
                    ]);
                    break;

                case 'last_6_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(6)->startOfDay()->toDateString(),
                        $now->toDateString()
                    ]);
                    break;

                case 'last_12_months':
                    $query->whereBetween('invoice_date', [
                        $now->copy()->subMonths(12)->startOfDay()->toDateString(),
                        $now->toDateString()
                    ]);
                    break;

                case 'this_year':
                    $query->whereYear('invoice_date', $now->year);
                    break;
            }
        }

        // 🔧 Order y paginación
        $invoices = $query->orderBy('invoice_date', 'desc')
                        ->paginate(20)
                        ->withQueryString();

        // 📊 Estadísticas para la vista
        $totalInvoices = Invoice::count();
        $draftCount = Invoice::where('status', 'draft')->count();
        $sentCount = Invoice::where('status', 'sent')->count();
        $paidCount = Invoice::where('status', 'paid')->count();

        // 🏢 Compañías para filtro
        $companies = User::whereNotNull('company_name')
                        ->whereHas('companyLocations')
                        ->orderBy('company_name')
                        ->get();

        // 📍 Estados para filtro
        $states = CompanyLocation::select('state')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');

        return view('admin.invoices.index', compact(
            'invoices',
            'companies',
            'states',
            'totalInvoices',
            'draftCount',
            'sentCount',
            'paidCount'
        ));
    }
    /**
     * ============================
     * FORMULARIO DE CREACIÓN
     * ============================
     */
    

    public function create()
    {
        $companies = User::whereNotNull('company_name')->get();
        $crews = Crew::where('is_active', true)->get(); // o simplemente Crew::all()

        $lastInvoice = \App\Models\Invoice::latest('id')->first();
        $nextNumber = 1000 + ($lastInvoice?->id ?? 0) + 1;
        $nextInvoiceNumber = 'INV-' . $nextNumber;

        return view('admin.invoices.create', compact('companies', 'nextInvoiceNumber', 'crews'));
    }


    /**
     * ============================
     * ITEMS POR ESTADO (CLAVE 🔑)
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
            ->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'name'     => $item->name,
                    'price'    => $item->price,
                    'category' => $item->category?->name ?? 'Uncategorized',
                ];
            });

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
            'crew_id' => 'nullable|exists:crews,id',
            'invoice_date'        => 'required|date',
            'due_date'            => 'nullable|date',
            'customer_email'      => 'nullable|email',
            'address'             => 'nullable|string|max:255',
            'bill_to'             => 'nullable|string|max:255',
            'status'              => 'nullable|in:draft,sent,paid',
            'tax'                 => 'nullable|numeric|min:0',

            'items'               => 'required|array|min:1',
            'items.*.id'          => 'required|integer',
            'items.*.name'        => 'required|string',
            'items.*.price'       => 'required|numeric|min:0',
            'items.*.quantity'    => 'required|integer|min:1',

            'memo'                => 'nullable|string',
            'notes'               => 'nullable|string',
            'attachments.*'       => 'file|max:10240',
        ]);

        DB::beginTransaction();

        try {

            // 1️⃣ CREATE INVOICE
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

            // 2️⃣ ITEMS (PRECIO CONGELADO)
            $subtotal = 0;

            foreach ($validated['items'] as $item) {

                $lineTotal = $item['price'] * $item['quantity'];
                $subtotal += $lineTotal;

                $invoice->items()->create([
                    'item_id'     => $item['id'],
                    'description' => $item['name'],
                    'price'       => $item['price'], // 👈 viene de item_prices
                    'quantity'    => $item['quantity'],
                    'total'       => $lineTotal,
                ]);
            }

            // 3️⃣ TOTALS
            $invoice->update([
                'subtotal' => $subtotal,
                'total'    => $subtotal + ($invoice->tax ?? 0),
            ]);

            // 4️⃣ ATTACHMENTS
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



    public function edit(Invoice $invoice)
    {
        $companies = User::whereNotNull('company_name')->get();
        $crews = Crew::where('is_active', true)->get(); // 👈 debes agregar esto

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
            'invoiceItems',
            'crews' // 👈 no olvides pasar los crews a la vista
        ));
    }




    public function update(Request $request, Invoice $invoice)
    {
            $validated = $request->validate([
                'company_location_id' => 'required|exists:company_locations,id',
                'crew_id'             => 'nullable|exists:crews,id', // 👈 AÑADIR
                'invoice_date'        => 'required|date',
                'due_date'            => 'nullable|date',
                'customer_email'      => 'nullable|email',
                'bill_to'             => 'nullable|string|max:255',
                'address'             => 'nullable|string|max:255',
                'status'              => 'required|in:draft,sent,paid',

                'items'               => 'required|array|min:1',
                'items.*.id'          => 'required|exists:items,id',
                'items.*.price'       => 'required|numeric|min:0',
                'items.*.quantity'    => 'required|integer|min:1',
            ]);


            DB::beginTransaction();

            try {

                /* =============================
                1️⃣ UPDATE INVOICE BASE
                ============================== */
                $invoice->update([
                    'company_location_id' => $validated['company_location_id'],
                    'crew_id'             => $validated['crew_id'] ?? null, // 👈 AÑADIR
                    'customer_email'      => $validated['customer_email'] ?? null,
                    'bill_to'             => $validated['bill_to'] ?? null,
                    'address'             => $validated['address'] ?? null, // ✅ NUEVO
                    'invoice_date'        => $validated['invoice_date'],
                    'due_date'            => $validated['due_date'] ?? null,
                    'status'              => $validated['status'],
                    'tax'                 => 0,
                ]);


                /* =============================
                2️⃣ RESET ITEMS (CLAVE 🔥)
                ============================== */
                $invoice->items()->delete();

                $subtotal = 0;

                foreach ($validated['items'] as $item) {

                    $lineTotal = $item['price'] * $item['quantity'];
                    $subtotal += $lineTotal;

                    $itemModel = Item::find($item['id']); // ✔ seguro por validation

                    $invoice->items()->create([
                        'item_id'     => $itemModel->id,
                        'description' => $itemModel->name, // ✔ NO dependes del JS
                        'price'       => $item['price'],
                        'quantity'    => $item['quantity'],
                        'total'       => $lineTotal,
                    ]);
                }

                /* =============================
                3️⃣ UPDATE TOTALS
                ============================== */
                $invoice->update([
                    'subtotal' => $subtotal,
                    'total'    => $subtotal,
                ]);

                DB::commit();

                return response()->json([
                    'success'    => true,
                    'invoice_id' => $invoice->id
                ]);

            } catch (\Throwable $e) {

                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }
    }




      /**
     * VER FACTURA
     */
    public function show(Invoice $invoice)
    {
        
        $invoice->load(['items', 'companyLocation.user']);
        return view('admin.invoices.show', compact('invoice'));
    }




    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return redirect()->back()->with('success', 'Invoice deleted successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }





    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['items', 'companyLocation', 'attachments']);

        $pdf = Pdf::loadView('admin.invoices.pdf_invoices', compact('invoice'))
                ->setPaper('a4');

        return $pdf->download("Factura-{$invoice->invoice_number}.pdf");
    }




    public function prepareInvoice(Invoice $invoice)
    {
        $invoice->load(['items.item', 'crew', 'payoutItems']);

        DB::transaction(function () use ($invoice) {

            foreach ($invoice->items as $invoiceItem) {

                $existingPayout = $invoice->payoutItems()
                    ->where('description', $invoiceItem->description)
                    ->first();

                $price = 0;

                if ($invoiceItem->item && $invoice->crew) {
                    $price = $invoiceItem->item->getCrewPrice(
                        $invoice->crew->has_trailer
                    );
                }

                // 🔥 Si no existe payout → crearlo
                if (!$existingPayout) {

                    $invoice->payoutItems()->create([
                        'description' => $invoiceItem->description,
                        'quantity'    => $invoiceItem->quantity,
                        'price'       => $price,
                        'total'       => $price * $invoiceItem->quantity,
                    ]);

                } else {

                    // 🔥 Si existe → sincronizar cantidad y total
                    if ($existingPayout->quantity != $invoiceItem->quantity) {

                        $existingPayout->update([
                            'quantity' => $invoiceItem->quantity,
                            'total'    => $existingPayout->price * $invoiceItem->quantity,
                        ]);
                    }
                }
            }
        });

        // 🔥 Cargar items con categoría para el select agrupado
        $availableItems = Item::with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $invoice->load('payoutItems');

        return view(
            'admin.invoices.prepare_payout',
            compact('invoice', 'availableItems')
        );
    }




    public function generateCustomPdf(Request $request, Invoice $invoice)
    {
        $request->validate([
            'address' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($request, $invoice) {

            // 🔥 Guardar dirección si cambió
            $invoice->update([
                'address' => $request->input('address', $invoice->address),
            ]);

            // 🔥 Borrar payout anterior
            $invoice->payoutItems()->delete();

            // 🔥 Guardar exactamente lo que viene del formulario
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

        return $pdf->download("Invoice-{$invoice->invoice_number}-Payout.pdf");
    }




}
