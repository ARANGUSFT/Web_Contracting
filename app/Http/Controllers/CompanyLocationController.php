<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyLocation;
use Illuminate\Http\Request;

class CompanyLocationController extends Controller
{

    public function byCompany($companyId)
    {
        return response()->json(
            CompanyLocation::where('user_id', $companyId)->get()
        );
    }








    public function index()
    {
        $locations = CompanyLocation::with('user')
            ->orderBy('state')
            ->get();

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'states'  => 'required|array',
            'states.*'=> 'string|max:5',
            'city'    => 'nullable|string|max:100',
        ]);

        foreach ($request->states as $state) {
            CompanyLocation::firstOrCreate(
                [
                    'user_id' => $request->user_id,
                    'state'   => strtoupper($state),
                ],
                [
                    'city' => $request->city,
                ]
            );
        }

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', 'Locations created successfully');
    }


    public function edit(CompanyLocation $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, CompanyLocation $location)
    {
        $request->validate([
            'state' => 'required|string|max:5',
            'city'  => 'nullable|string|max:100',
        ]);

        $location->update([
            'state' => strtoupper($request->state),
            'city'  => $request->city,
        ]);

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', 'Location updated successfully');
    }


    public function destroy(CompanyLocation $location)
    {
        $location->delete();

        return back()->with('success','Location deleted');
    }

    
}
