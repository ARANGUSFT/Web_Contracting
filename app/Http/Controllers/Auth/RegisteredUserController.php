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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'phone' => ['nullable', 'string', 'max:20'],
            'language' => ['nullable', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // máx 2MB
            'company_name' => ['nullable', 'string', 'max:255'],
            'residential_roof_types' => ['nullable', 'array'],
            'commercial_roof_types' => ['nullable', 'array'],
            'states_you_can_work' => ['nullable', 'string'],
            'all_states' => ['nullable', 'boolean'],
            'years_experience' => ['nullable', 'string', 'max:10'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Guardar imagen si viene
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // Convertir estados a array (si vienen separados por coma)
        $statesArray = [];
        if ($request->filled('states_you_can_work')) {
            $statesArray = array_map('trim', explode(',', $request->input('states_you_can_work')));
        }

        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'language' => $request->language ?? 'English',
            'profile_photo' => $photoPath,
            'company_name' => $request->company_name,
            'residential_roof_types' => $request->residential_roof_types ?? [],
            'commercial_roof_types' => $request->commercial_roof_types ?? [],
            'states_you_can_work' => $statesArray,
            'all_states' => $request->boolean('all_states'),
            'years_experience' => $request->years_experience,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
