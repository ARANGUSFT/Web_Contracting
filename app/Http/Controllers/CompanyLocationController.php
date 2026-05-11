<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyLocationController extends Controller
{
    /**
     * List all locations grouped by company → state.
     */
    public function index()
    {
        $locations = CompanyLocation::with('user')
            ->orderBy('state')
            ->orderByRaw('CASE WHEN city IS NULL THEN 0 ELSE 1 END') // base price first, cross-DB compatible
            ->orderBy('city')
            ->get()
            ->groupBy([
                'user_id',
                'state',
            ]);

        return view('admin.locations.index', compact('locations'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $companies = User::where('is_admin', 0)
            ->orderBy('company_name')
            ->get();

        return view('admin.locations.create', compact('companies'));
    }

    /**
     * Store one or more locations (bulk, skips duplicates silently).
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'locations'         => 'required|array|min:1',
            'locations.*.state' => 'required|string|max:5',
            'locations.*.city'  => 'nullable|string|max:100',
        ]);

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($request, &$created, &$skipped) {
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
                    $skipped++;
                    continue;
                }

                CompanyLocation::create([
                    'user_id' => $request->user_id,
                    'state'   => $state,
                    'city'    => $city,
                ]);

                $created++;
            }
        });

        $message = $created > 0
            ? "{$created} location" . ($created > 1 ? 's' : '') . " created successfully."
            : "No new locations were added.";

        if ($skipped > 0) {
            $message .= " {$skipped} duplicate" . ($skipped > 1 ? 's were' : ' was') . " skipped.";
        }

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', $message);
    }

    /**
     * Show the edit form.
     */
    public function edit(CompanyLocation $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    /**
     * Update a location.
     */
    public function update(Request $request, CompanyLocation $location)
    {
        $request->validate([
            'state' => 'required|string|max:5',
            'city'  => 'nullable|string|max:100',
        ]);

        $state = strtoupper(trim($request->state));
        $city  = $request->city ? trim($request->city) : null;

        $duplicate = CompanyLocation::where('user_id', $location->user_id)
            ->where('state', $state)
            ->where('city', $city)
            ->where('id', '!=', $location->id)
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['state' => 'A location with this state and city already exists for this company.'])
                ->withInput();
        }

        $location->update([
            'state' => $state,
            'city'  => $city,
        ]);

        return redirect()
            ->route('superadmin.locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Delete a location.
     */
    public function destroy(CompanyLocation $location)
    {
        $location->delete();

        return back()->with('success', 'Location deleted successfully.');
    }

    /**
     * AJAX — return all locations for a given company.
     * Used in invoices, price selectors, etc.
     * Route is already protected by the superadmin middleware in web.php.
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

    /**
     * Show all locations for a specific company (manage view).
     */
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

    /**
     * Add one or more locations to a specific company (from the manage view).
     */
    public function storeForCompany(Request $request, User $company)
    {
        $request->validate([
            'locations'         => 'required|array|min:1',
            'locations.*.state' => 'required|string|max:5',
            'locations.*.city'  => 'nullable|string|max:100',
        ]);

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($request, $company, &$created, &$skipped) {
            foreach ($request->locations as $loc) {

                $state = strtoupper(trim($loc['state']));
                $city  = isset($loc['city']) && trim($loc['city']) !== ''
                    ? trim($loc['city'])
                    : null;

                $exists = CompanyLocation::where('user_id', $company->id)
                    ->where('state', $state)
                    ->where('city', $city)
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                CompanyLocation::create([
                    'user_id' => $company->id,
                    'state'   => $state,
                    'city'    => $city,
                ]);

                $created++;
            }
        });

        $message = $created > 0
            ? "{$created} location" . ($created > 1 ? 's' : '') . " added successfully."
            : "No new locations were added.";

        if ($skipped > 0) {
            $message .= " {$skipped} duplicate" . ($skipped > 1 ? 's were' : ' was') . " skipped.";
        }

        return back()->with('success', $message);
    }
}