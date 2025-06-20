<?php

namespace App\Http\Controllers;

use App\Models\Subcontractors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SubcontractorsController extends Controller
{
    public function index(Request $request)
    {
        $query = Subcontractors::query();
        
        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $status = $request->input('status') === 'active';
            $query->where('is_active', $status);
        }
        
        if ($request->filled('state')) {
            $query->where('state', $request->input('state'));
        }
        
        $subcontractors = $query->latest()->paginate(10);
        
        // Obtener estados únicos para el dropdown de filtro
        $states = Subcontractors::select('state')
            ->distinct()
            ->orderBy('state')
            ->pluck('state');
            
        return view('admin.subcontractors.index', compact('subcontractors', 'states'));
    }

    public function create()
    {
        return view('admin.subcontractors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:subcontractors,email',
            'phone' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['is_active'] = $request->boolean('is_active');

        Subcontractors::create($validated);

        return redirect()->route('superadmin.subcontractors.index')->with('success', 'Subcontractor created successfully.');
    }

    public function edit(Subcontractors $subcontractor)
    {
        return view('admin.subcontractors.edit', compact('subcontractor'));
    }

    public function update(Request $request, Subcontractors $subcontractor)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:subcontractors,email,' . $subcontractor->id,
            'phone' => 'nullable|string|max:255',
            'state' => 'required|string|max:255',
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }
        
        $validated['is_active'] = $request->has('is_active');

        $subcontractor->update($validated);

        return redirect()->route('superadmin.subcontractors.index')->with('success', 'Subcontractor updated successfully.');
    }

    public function destroy(Subcontractors $subcontractor)
    {
        $subcontractor->delete();
        return redirect()->route('superadmin.subcontractors.index')->with('success', 'Subcontractor deleted successfully.');
    }
}