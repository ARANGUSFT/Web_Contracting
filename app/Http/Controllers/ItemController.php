<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(CompanyLocation $location)
    {
        // Si es llamada AJAX (fetch)
        if (request()->ajax()) {
            return response()->json(
                $location->items()->get()
            );
        }

        // Si es vista normal
        return view('admin.locations.items.index', [
            'location' => $location,
            'items' => $location->items
        ]);
    }

    public function itemsJson(CompanyLocation $location)
    {
        return response()->json(
            $location->items()->select('id', 'name', 'price')->get()
        );
    }



    public function create(CompanyLocation $location)
    {
        return view('admin.locations.items.create', compact('location'));
    }

    public function store(Request $request, CompanyLocation $location)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        Item::create([
            'company_location_id' => $location->id,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'is_active'   => $request->is_active ? 1 : 0,
        ]);

        return redirect()
            ->route('superadmin.locations.items.index', $location)
            ->with('success', 'Item created successfully');
    }


    public function edit(CompanyLocation $location, Item $item)
    {
        // Seguridad: asegurar que el item pertenece al estado
        if ($item->company_location_id !== $location->id) {
            abort(404);
        }

        return view(
            'admin.locations.items.edit',
            compact('location', 'item')
        );
    }

    public function update(Request $request, CompanyLocation $location, Item $item)
    {
        if ($item->company_location_id !== $location->id) {
            abort(404);
        }

        $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'is_active'   => 'nullable|boolean',
        ]);

        $item->update([
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
            'is_active'   => $request->is_active ? 1 : 0,
        ]);

        return redirect()
            ->route('superadmin.locations.items.index', $location)
            ->with('success', 'Item updated successfully');
    }

    public function destroy(CompanyLocation $location, Item $item)
    {
        if ($item->company_location_id !== $location->id) {
            abort(404);
        }

        $item->delete();

        return redirect()
            ->route('superadmin.locations.items.index', $location)
            ->with('success', 'Item deleted successfully');
    }

}
