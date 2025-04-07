<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|confirmed|unique:users,email',
            'language' => 'required|string|max:20',
            'profile_photo' => 'nullable|image|max:2048',
            'company_name' => 'nullable|string|max:255',
            'years_experience' => 'nullable|string|max:50',
            'residential_roof_types' => 'nullable|array',
            'commercial_roof_types' => 'nullable|array',
            'states_you_can_work' => 'nullable|array',
            'all_states' => 'nullable|boolean',
            'company_documents.*' => 'file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);


        $user = new User();
        $user->name = $validated['name'];
        $user->last_name = $validated['last_name'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->email = $validated['email'];
        $user->language = $validated['language'];
        $user->company_name = $validated['company_name'] ?? null;
        $user->years_experience = $validated['years_experience'] ?? null;
        $user->states_you_can_work = $validated['states_you_can_work'] ?? null;
        $user->all_states = $request->has('all_states');

        // Profile photo
        if ($request->hasFile('profile_photo')) {
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Roof types as JSON
        $user->residential_roof_types = $request->has('residential_roof_types')
            ? json_encode($validated['residential_roof_types'])
            : null;

        $user->commercial_roof_types = $request->has('commercial_roof_types')
            ? json_encode($validated['commercial_roof_types'])
            : null;
            $documents = [];

            if ($request->hasFile('company_documents')) {
                foreach ($request->file('company_documents') as $file) {
                    $path = $file->store('company_documents', 'public');
                    $documents[] = [
                        'file_name' => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ];
                }
            }
            
            $user->company_documents = $documents;
            

        // Password
        $user->password = Hash::make($validated['password']);

        $user->save();

        auth()->login($user);

        return redirect()->route('dashboard'); // o donde quieras redirigir
    }
    
}
