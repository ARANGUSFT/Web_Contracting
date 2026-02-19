<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{
    /**
     * 📄 Listado de categorías
     */
    public function index()
    {
        $categories = ItemCategory::orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.items.categories.index', compact('categories'));
    }

    /**
     * ➕ Formulario crear categoría
     */
    public function create()
    {
        return view('admin.items.categories.create');
    }

    /**
     * 💾 Guardar categoría
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        ItemCategory::create([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('superadmin.item-categories.index')
            ->with('success', 'Category created successfully');
    }

    /**
     * ✏️ Editar categoría
     */
    public function edit(ItemCategory $itemCategory)
    {
        return view('admin.items.categories.edit', compact('itemCategory'));
    }

    /**
     * 🔄 Actualizar categoría
     */
    public function update(Request $request, ItemCategory $itemCategory)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $itemCategory->update([
            'name'       => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active'  => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('superadmin.item-categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * 🗑 Eliminar categoría
     * (los items quedan sin categoría por nullOnDelete)
     */
    public function destroy(ItemCategory $itemCategory)
    {
        $itemCategory->delete();

        return back()->with('success', 'Category deleted successfully');
    }
}
