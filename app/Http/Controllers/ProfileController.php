<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


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
    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'language' => 'required|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
            'company_name' => 'nullable|string|max:255',
            'years_experience' => 'nullable|string|max:50',
            'residential_roof_types' => 'nullable|array',
            'commercial_roof_types' => 'nullable|array',
            'states_you_can_work' => 'nullable|array',
            'all_states' => 'nullable|boolean',
            'company_documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);
    
        $user->name = $validated['name'];
        $user->last_name = $validated['last_name'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->email = $validated['email'];
        $user->language = $validated['language'];
        $user->company_name = $validated['company_name'] ?? null;
        $user->years_experience = $validated['years_experience'] ?? null;
        $user->states_you_can_work = $validated['states_you_can_work'] ?? null;
        $user->all_states = $request->has('all_states');
    
        // Foto de perfil
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }
    
        // Tipos de techo (cast automático)
        $user->residential_roof_types = $validated['residential_roof_types'] ?? null;
        $user->commercial_roof_types = $validated['commercial_roof_types'] ?? null;
    


        $documents = $user->company_documents ?? [];

        if ($request->hasFile('company_documents')) {
            foreach ($request->file('company_documents') as $file) {
                $path = $file->store('company_documents', 'public');
                $documents[] = [
                    'file_name' => $path,
                    'original_name' => $file->getClientOriginalName(),
                ];
            }
        
            $user->company_documents = $documents;
        }
        


            


    
        $user->save();
    
        return back()->with('success', 'Document deleted.');
    }
    



    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        // Verifica si la contraseña actual es correcta
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Cambiar contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function deleteCompanyDocument($index)
    {
        $user = auth()->user();
        $documents = $user->company_documents;

        // Validar existencia del índice
        if (!is_array($documents) || !isset($documents[$index])) {
            return back()->with('error', 'Document not found.');
        }

        // Obtener la ruta del archivo (soporta formato antiguo y nuevo)
        $path = is_array($documents[$index])
            ? $documents[$index]['file_name']
            : $documents[$index];

        // Borrar archivo físico si existe
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        // Eliminar el documento del array
        unset($documents[$index]);
        $documents = array_values($documents); // Reindexar

        // Guardar el nuevo arreglo o null si está vacío
        $user->company_documents = empty($documents) ? null : $documents;
        $user->save();

        return back()->with('success', 'Document deleted.');
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
