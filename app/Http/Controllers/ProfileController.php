<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Procesar y guardar foto de perfil si se carga
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        // ✅ Subir documentos nuevos si se envían
        if ($request->hasFile('company_documents')) {
            $existingDocs = $user->company_documents ?? [];
            foreach ($request->file('company_documents') as $file) {
                $existingDocs[] = $file->store('company_documents', 'public');
            }
            $user->company_documents = $existingDocs;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Formatear número de teléfono completo
        $phone = trim($request->input('phone_country_code') . ' ' . $request->input('phone'));

        // Actualizar datos
        $user->fill([
            'name' => $request->input('name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'phone' => $phone,
            'language' => $request->input('language', 'English'),
            'company_name' => $request->input('company_name'),
            'residential_roof_types' => $request->input('residential_roof_types', []),
            'commercial_roof_types' => $request->input('commercial_roof_types', []),
            'states_you_can_work' => $request->filled('states_you_can_work')
                ? array_map('trim', explode(',', $request->input('states_you_can_work')))
                : [],
            'all_states' => $request->boolean('all_states'),
            'years_experience' => $request->input('years_experience'),
        ]);

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
