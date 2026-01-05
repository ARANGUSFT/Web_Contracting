<?php

namespace App\Http\Controllers;

use App\Models\Crew;
use App\Models\Subcontractors;
use Illuminate\Http\Request;

class CrewController extends Controller
{
    public function index(Request $request)
{
    $query = Crew::with('subcontractors')->latest();

    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('states')) {
        $query->whereJsonContains('states', $request->states);
    }

    if ($request->filled('status')) {
        if ($request->status === 'active') {
            $query->where('is_active', true);
        } elseif ($request->status === 'inactive') {
            $query->where('is_active', false);
        }
    }

    // ✅ Status filter
    if ($request->status === 'active') {
        $query->where('is_active', true);
    } elseif ($request->status === 'inactive') {
        $query->where('is_active', false);
    }

    // 🚚 Trailer filter (NUEVO)
    if ($request->filled('trailer')) {
        $query->where('has_trailer', (bool) $request->trailer);
    }

    $crews = $query->paginate(10)->appends($request->query());

    return view('admin.crew.index', compact('crews'));
}

    

    public function create()
    {
        $subcontractors = Subcontractors::where('is_active', true)->get();

        return view('admin.crew.create', compact('subcontractors'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|unique:crews,email',
            'phone' => 'nullable|string|max:255',
            'states' => 'nullable|array',
            'states.*' => 'string|max:2',
            'is_active' => 'nullable|in:0,1',
            'has_trailer' => 'nullable|in:0,1', // ✅ NUEVO
            'subcontractors' => 'array|nullable',
            'subcontractors.*' => 'exists:subcontractors,id',
        ]);

    
        $crew = Crew::create([
            'name'        => $validated['name'],
            'company'     => $validated['company'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'states'      => $validated['states'] ?? [],
            'is_active'   => $request->boolean('is_active'),
            'has_trailer' => $request->boolean('has_trailer'), // ✅ CLAVE
        ]);

    
        if (!empty($validated['subcontractors'])) {
            $crew->subcontractors()->sync($validated['subcontractors']);
        }
    
        return redirect()->route('superadmin.crew.index')->with('success', 'Crew created successfully.');
    }
    
    

    public function edit(Crew $crew)
    {
        $subcontractors = Subcontractors::where('is_active', true)->get();
        $crew->load('subcontractors');

        return view('admin.crew.edit', compact('crew', 'subcontractors'));
    }


    public function update(Request $request, Crew $crew)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|email|unique:crews,email,' . $crew->id,
            'phone' => 'nullable|string|max:255',
            'states' => 'nullable|array',
            'states.*' => 'string|max:2',
            'is_active' => 'nullable|in:0,1',
            'has_trailer' => 'nullable|in:0,1', // ✅ NUEVO
            'subcontractors' => 'array|nullable',
            'subcontractors.*' => 'exists:subcontractors,id',
        ]);

    
        $crew->update([
            'name'        => $validated['name'],
            'company'     => $validated['company'],
            'email'       => $validated['email'],
            'phone'       => $validated['phone'] ?? null,
            'states'      => $validated['states'] ?? [],
            'is_active'   => $request->boolean('is_active'),
            'has_trailer' => $request->boolean('has_trailer'), // ✅ CLAVE
        ]);

    
        $crew->subcontractors()->sync($validated['subcontractors'] ?? []);
    
        return redirect()->route('superadmin.crew.index')->with('success', 'Crew updated successfully.');
    }
    


    public function assign(Crew $crew)
    {
        $subcontractors = Subcontractors::where('is_active', true)->get();
        $crew->load('subcontractors');
        return view('admin.crew.assign', compact('crew', 'subcontractors'));
    }

    public function assignStore(Request $request, Crew $crew)
    {
        $validated = $request->validate([
            'subcontractors' => 'array|nullable',
            'subcontractors.*' => 'exists:subcontractors,id',
        ]);

        $crew->subcontractors()->sync($request->subcontractors ?? []);
        return redirect()->route('superadmin.crew.index')->with('success', 'Subcontractors assigned successfully.');
    }


    public function destroy(Crew $crew)
    {
        $crew->subcontractors()->detach();
        $crew->delete();

        return redirect()->route('superadmin.crew.index')->with('success', 'Crew deleted successfully.');
    }
}
