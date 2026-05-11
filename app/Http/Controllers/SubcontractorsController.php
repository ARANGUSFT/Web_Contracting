<?php

namespace App\Http\Controllers;

use App\Mail\SubcontractorWelcomeMail;
use App\Models\Subcontractors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

            'residential_roof_types' => 'nullable|array',
            'residential_roof_types.*' => 'string|max:255',

            'commercial_roof_types' => 'nullable|array',
            'commercial_roof_types.*' => 'string|max:255',

            'states_you_can_work' => 'nullable|array',
            'states_you_can_work.*' => 'string|max:255',

            'all_states' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Guardar la contraseña en texto plano ANTES de hashearla
        // (la necesitamos para enviarla por correo)
        $plainPassword = $validated['password'];

        // Hashear y preparar el resto
        $validated['password']                 = Hash::make($validated['password']);
        $validated['is_active']                = $request->has('is_active');
        $validated['all_states']               = $request->boolean('all_states');
        $validated['residential_roof_types']   = $request->input('residential_roof_types', []);
        $validated['commercial_roof_types']    = $request->input('commercial_roof_types', []);
        $validated['states_you_can_work']      = $request->input('states_you_can_work', []);

        // Crear el subcontratista
        $subcontractor = Subcontractors::create($validated);

        // Enviar correo de bienvenida con credenciales
        try {
            Mail::to($subcontractor->email)
                ->send(new SubcontractorWelcomeMail($subcontractor, $plainPassword));

            return redirect()
                ->route('superadmin.subcontractors.index')
                ->with('success', "Subcontractor created successfully. Welcome email sent to {$subcontractor->email}.");
        } catch (\Exception $e) {
            // Loguear pero NO impedir el flujo — la cuenta ya se creó
            Log::error('Failed to send subcontractor welcome email', [
                'subcontractor_id' => $subcontractor->id,
                'email'            => $subcontractor->email,
                'error'            => $e->getMessage(),
            ]);

            return redirect()
                ->route('superadmin.subcontractors.index')
                ->with('warning', "Subcontractor created, but the welcome email could not be sent. Please verify {$subcontractor->email} and resend manually.");
        }
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

            'residential_roof_types' => 'nullable|array',
            'residential_roof_types.*' => 'string|max:255',

            'commercial_roof_types' => 'nullable|array',
            'commercial_roof_types.*' => 'string|max:255',

            'states_you_can_work' => 'nullable|array',
            'states_you_can_work.*' => 'string|max:255',

            'all_states' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Detectar si cambió la contraseña para reenviar correo
        $passwordChanged = false;
        $plainPassword   = null;

        if ($request->filled('password')) {
            $plainPassword           = $validated['password'];
            $validated['password']   = Hash::make($request->password);
            $passwordChanged         = true;
        } else {
            unset($validated['password']);
        }

        $validated['is_active']  = $request->boolean('is_active');
        $validated['all_states'] = $request->boolean('all_states');

        $validated['residential_roof_types'] = $request->input('residential_roof_types', []);
        $validated['commercial_roof_types']  = $request->input('commercial_roof_types', []);
        $validated['states_you_can_work']    = $request->input('states_you_can_work', []);

        $subcontractor->update($validated);

        // Si cambió la contraseña, reenviar correo con las nuevas credenciales
        if ($passwordChanged) {
            try {
                Mail::to($subcontractor->email)
                    ->send(new SubcontractorWelcomeMail($subcontractor, $plainPassword));

                return redirect()
                    ->route('superadmin.subcontractors.index')
                    ->with('success', "Subcontractor updated. New password emailed to {$subcontractor->email}.");
            } catch (\Exception $e) {
                Log::error('Failed to send subcontractor password reset email', [
                    'subcontractor_id' => $subcontractor->id,
                    'error'            => $e->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('superadmin.subcontractors.index')
            ->with('success', 'Subcontractor updated successfully.');
    }

    public function destroy(Subcontractors $subcontractor)
    {
        $subcontractor->delete();
        return redirect()
            ->route('superadmin.subcontractors.index')
            ->with('success', 'Subcontractor deleted successfully.');
    }
}