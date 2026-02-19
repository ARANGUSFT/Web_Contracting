<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Listado de items globales con filtros
     */
    public function index(Request $request)
    {
        $query = Item::with('category');

        /* ================= SEARCH ================= */
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        /* ================= STATUS ================= */
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        /* ================= PRICE ================= */
        if ($request->filled('price_filter')) {
            if ($request->price_filter === 'with_price') {
                $query->whereNotNull('global_price')
                      ->where('global_price', '>', 0);
            }

            if ($request->price_filter === 'without_price') {
                $query->where(function ($q) {
                    $q->whereNull('global_price')
                      ->orWhere('global_price', 0);
                });
            }
        }

        /* ================= CATEGORY ================= */
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        /* ================= SORT ================= */
        $sortColumn = $request->get('sort_by', 'sort_order');
        $sortDirection = $request->get('sort_dir') === 'desc' ? 'desc' : 'asc';

        $validColumns = [
            'name',
            'global_price',
            'crew_price_with_trailer',
            'crew_price_without_trailer',
            'sort_order',
            'updated_at'
        ];

        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'sort_order';
        }

        $query->orderBy($sortColumn, $sortDirection);

        /* ================= PAGINATION ================= */
        $items = $query->paginate(10)->withQueryString();

        /* ================= STATS ================= */
        $totalItems = Item::count();
        $activeItems = Item::where('is_active', true)->count();
        $itemsWithGlobalPrice = Item::whereNotNull('global_price')
            ->where('global_price', '>', 0)
            ->count();

        $itemsWithCrewTrailerPrice = Item::whereNotNull('crew_price_with_trailer')
            ->where('crew_price_with_trailer', '>', 0)
            ->count();

        $itemsWithCrewNoTrailerPrice = Item::whereNotNull('crew_price_without_trailer')
            ->where('crew_price_without_trailer', '>', 0)
            ->count();


        /* ================= CATEGORIES ================= */
        $categories = ItemCategory::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.items.index', compact(
            'items',
            'categories',
            'totalItems',
            'activeItems',
            'itemsWithGlobalPrice',
            'itemsWithCrewTrailerPrice',
            'itemsWithCrewNoTrailerPrice'
        ));

    }

    /**
     * Form crear item
     */
    public function create()
    {
        $categories = ItemCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.items.create', compact('categories'));
    }

    /**
     * Guardar item
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'category_id'  => [
                'nullable',
                Rule::exists('item_categories', 'id')
                    ->where('is_active', true),
            ],
            'global_price'               => 'nullable|numeric|min:0',
            'crew_price_with_trailer'    => 'nullable|numeric|min:0',
            'crew_price_without_trailer' => 'nullable|numeric|min:0',
            'sort_order'   => 'nullable|integer',
            'is_active'    => 'nullable|boolean',
        ]);

        Item::create([
            'name'                       => $request->name,
            'category_id'                => $request->category_id,
            'global_price'               => $request->global_price,
            'crew_price_with_trailer'    => $request->crew_price_with_trailer,
            'crew_price_without_trailer' => $request->crew_price_without_trailer,
            'sort_order'                 => $request->sort_order ?? 0,
            'is_active'                  => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('superadmin.items.index')
            ->with('success', 'Item created successfully');
    }


    /**
     * Editar item
     */
    public function edit(Item $item)
    {
        $categories = ItemCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.items.edit', compact('item', 'categories'));
    }

    /**
     * Actualizar item
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name'                       => 'required|string|max:150',
            'category_id'                => 'nullable|exists:item_categories,id',
            'global_price'               => 'nullable|numeric|min:0',
            'crew_price_with_trailer'    => 'nullable|numeric|min:0',
            'crew_price_without_trailer' => 'nullable|numeric|min:0',
            'sort_order'                 => 'nullable|integer',
            'is_active'                  => 'nullable|boolean',
        ]);

        $item->update([
            'name'                       => $request->name,
            'category_id'                => $request->category_id,
            'global_price'               => $request->global_price,
            'crew_price_with_trailer'    => $request->crew_price_with_trailer,
            'crew_price_without_trailer' => $request->crew_price_without_trailer,
            'sort_order'                 => $request->sort_order ?? 0,
            'is_active'                  => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('superadmin.items.index')
            ->with('success', 'Item updated successfully');
    }


    /**
     * Eliminar item
     */
    public function destroy(Item $item)
    {
        if ($item->prices()->exists()) {
            return back()->withErrors(
                'This item cannot be deleted because it has pricing overrides.'
            );
        }

        $item->delete();

        return back()->with('success', 'Item deleted successfully');
    }

    /**
     * Duplicar item
     */
    public function duplicate(Item $item)
    {
        $newItem = $item->replicate();
        $newItem->name = $newItem->name . ' (Copy)';
        $newItem->is_active = false;
        $newItem->save();

        return redirect()
            ->route('superadmin.items.index')
            ->with('success', 'Item duplicated successfully');
    }
}
