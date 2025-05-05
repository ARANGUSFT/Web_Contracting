<?php

namespace App\Http\Controllers;

use App\Models\Emergencies;
use Illuminate\Http\Request;

class EmergenciesController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_submitted' => 'required|date',
            'type_of_supplement' => 'required|string',
            'company_name' => 'required|string',
            'company_contact_email' => 'required|email',
            'job_number_name' => 'required|string',
            'job_address' => 'required|string',
            'job_address_line2' => 'nullable|string',
            'job_city' => 'required|string',
            'job_state' => 'required|string',
            'job_zip_code' => 'required|string',
            'terms_conditions' => 'accepted',
            'requirements' => 'accepted',
            'aerial_measurement.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'contract_upload.*' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'file_picture_upload.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $aerialPaths = [];
        foreach ($request->file('aerial_measurement', []) as $file) {
            $aerialPaths[] = $file->store('emergency/aerials', 'public');
        }

        $contractPaths = [];
        foreach ($request->file('contract_upload', []) as $file) {
            $contractPaths[] = $file->store('emergency/contracts', 'public');
        }

        $filePicturePaths = [];
        if ($request->hasFile('file_picture_upload')) {
            foreach ($request->file('file_picture_upload') as $file) {
                $filePicturePaths[] = $file->store('emergency/files', 'public');
            }
        }

        Emergencies::create([
            ...$validated,
            'terms_conditions' => $request->has('terms_conditions'),
            'requirements' => $request->has('requirements'),
            'aerial_measurement_path' => json_encode($aerialPaths),
            'contract_upload_path' => json_encode($contractPaths),
            'file_picture_upload_path' => json_encode($filePicturePaths),
        ]);

        return redirect()->back()->with('success', 'Emergency request submitted successfully.');
    }

    public function form()
    {
        $user = auth()->user();
        return view('leads.pg.emergency', compact('user'));
    }
}
