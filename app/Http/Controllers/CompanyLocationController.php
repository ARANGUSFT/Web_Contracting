<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\ItemPrice;
use Illuminate\Support\Facades\DB;

class CompanyLocationController extends Controller
{
    /**
     * Listado de ubicaciones por empresa
     * (empresa + estado + ciudad)
     */
    public function index()
    {
        $locations = CompanyLocation::with('user')
            ->orderBy('state')
            ->orderByRaw('city IS NOT NULL') // state base primero
            ->orderBy('city')
            ->get()
            ->groupBy([
                'user_id',
                'state',
            ]);

        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        $companies = User::where('is_admin', 0)
            ->orderBy('company_name')
            ->get();

        return view('admin.locations.create', compact('companies'));
    }
    /**
     * Formulario para crear ubicación
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'            => 'required|exists:users,id',
            'locations'          => 'required|array|min:1',
            'locations.*.state'  => 'required|string|max:5',
            'locations.*.city'   => 'nullable|string|max:100',
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->locations as $loc) {

                $state = strtoupper(trim($loc['state']));
                $city  = isset($loc['city']) && trim($loc['city']) !== ''
                    ? trim($loc['city'])
                    : null;

                $exists = CompanyLocation::where('user_id', $request->user_id)
                    ->where('state', $state)
                    ->where('city', $city)
                    ->exists();

                if ($exists) {
                    continue; // saltamos duplicados sin romper
                }

                CompanyLocation::create([
                    'user_id' => $request->user_id,
                    'state'   => $state,
                    'city'    => $city,
                ]);
            }
        });

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', 'Locations created successfully');
    }






    /**
     * Editar ubicación
     */
    public function edit(CompanyLocation $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Actualizar ubicación
     */
    public function update(Request $request, CompanyLocation $location)
    {
        $request->validate([
            'state' => 'required|string|max:5',
            'city'  => 'nullable|string|max:100',
        ]);

        // 🔒 Evitar duplicados al actualizar
        $exists = CompanyLocation::where('user_id', $location->user_id)
            ->where('state', strtoupper($request->state))
            ->where('city', $request->city)
            ->where('id', '!=', $location->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors('Another location with the same state and city already exists.')
                ->withInput();
        }

        $location->update([
            'state' => strtoupper($request->state),
            'city'  => $request->city,
        ]);

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', 'Location updated successfully');
    }

    /**
     * Eliminar ubicación
     */
    public function destroy(CompanyLocation $location)
    {
        $location->delete();

        return back()->with('success', 'Location deleted successfully');
    }

    /**
     * AJAX – obtener ubicaciones por empresa
     * (usado en invoices, prices, etc.)
     */
    public function byCompany($companyId)
    {
        $locations = CompanyLocation::where('user_id', $companyId)
            ->select('id', 'state', 'city')
            ->orderBy('state')
            ->orderBy('city')
            ->get();

        return response()->json($locations);
    }








    
    public function manageCompanyLocations(User $user)
    {
        $locations = CompanyLocation::where('user_id', $user->id)
            ->orderBy('state')
            ->orderBy('city')
            ->get()
            ->groupBy('state');

        return view('admin.locations.manage', compact('user', 'locations'));
    }

    public function manage(User $company)
    {
        $company->load('companyLocations');

        $locations = $company->companyLocations
            ->sortBy(fn($l) => $l->state . '|' . ($l->city ?? ''))
            ->groupBy('state');

        return view('admin.locations.manage', [
            'company'   => $company,
            'locations' => $locations,
        ]);
    }

    public function storeForCompany(Request $request, User $company)
    {
        $request->validate([
            'locations'          => 'required|array|min:1',
            'locations.*.state'  => 'required|string|max:5',
            'locations.*.city'   => 'nullable|string|max:100',
        ]);

        foreach ($request->locations as $loc) {
            $state = strtoupper(trim($loc['state']));
            $city  = isset($loc['city']) && trim($loc['city']) !== ''
                ? trim($loc['city'])
                : null;

            $exists = CompanyLocation::where('user_id', $company->id)
                ->where('state', $state)
                ->where('city', $city)
                ->exists();

            if ($exists) continue;

            CompanyLocation::create([
                'user_id' => $company->id,
                'state'   => $state,
                'city'    => $city,
            ]);
        }

        return back()->with('success', 'Locations added successfully');
    }



}
