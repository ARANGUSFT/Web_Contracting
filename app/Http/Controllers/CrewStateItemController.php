<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\CrewStateItem;
use Illuminate\Http\Request;

class CrewStateItemController extends Controller
{
    // 1️⃣ Estados de la crew
    public function states(Crew $crew)
    {
        return view('admin.crew.states', compact('crew'));
    }

    // 2️⃣ Items por estado
    public function index(Crew $crew, string $state)
    {
        $items = CrewStateItem::where('crew_id', $crew->id)
            ->where('state', $state)
            ->orderBy('name')
            ->get();

        return view(
            'admin.crew.state-items.index',
            compact('crew','state','items')
        );
    }

    // 3️⃣ Guardar item
    public function store(Request $request, Crew $crew, string $state)
    {
        $request->validate([
            'name'  => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
        ]);

        CrewStateItem::create([
            'crew_id'     => $crew->id,
            'state'       => $state,
            'name'        => $request->name,
            'description' => $request->description,
            'price'       => $request->price,
        ]);

        return back()->with('success', 'Item created');
    }

    public function edit(Crew $crew, string $state, CrewStateItem $item)
    {
        return view('admin.crew.state-items.edit', compact('crew', 'state', 'item'));
    }

    public function update(Request $request, Crew $crew, string $state, CrewStateItem $item)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return redirect()
            ->route('superadmin.crews.states.items.index', [$crew->id, $state])
            ->with('success', 'Item updated successfully.');
    }


    // 4️⃣ Eliminar
    public function destroy(CrewStateItem $item)
    {
        $item->delete();
        return back()->with('success','Item deleted');
    }
}
