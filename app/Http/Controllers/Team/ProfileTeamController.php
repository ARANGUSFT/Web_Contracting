<?php

namespace App\Http\Controllers\Team;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;

class ProfileTeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:team');
    }

    // Mostrar formulario de edición de perfil
    public function edit()
    {
        $user = Auth::guard('team')->user();
        return view('teamProfile.edit', compact('user'));
    }

    // Guardar cambios de perfil
    public function update(Request $request)
    {
        $user = Auth::guard('team')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('team')->ignore($user->id)],
        ]);

        $user->update($request->only(['name', 'email']));

        return back()->with('success', 'Profile updated successfully.');
    }

    // Cambiar contraseña
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('team')->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is not valid.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated correctly');
    }
}
