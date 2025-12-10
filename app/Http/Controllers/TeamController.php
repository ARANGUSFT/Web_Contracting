<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Team;
use App\Notifications\UserCredentialsNotification;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::where('user_id', auth()->id())->paginate(10);
        return view('manageTeam.main', compact('teams'));
    }

    public function create()
    {
        return view('manageTeam.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team,email',
            'phone' => [
                'nullable',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        // Limpiar el número (quitar espacios, guiones, paréntesis)
                        $cleaned = preg_replace('/[^\d+]/', '', $value);
                        
                        // Debe empezar con +1
                        if (!str_starts_with($cleaned, '+1')) {
                            $fail('Los números de Estados Unidos deben comenzar con +1.');
                        }
                        
                        // Verificar longitud (+1 + 10 dígitos = 11 caracteres numéricos)
                        if (strlen($cleaned) !== 12) { // +1 es 2 caracteres + 10 dígitos
                            $fail('El número de teléfono debe tener 10 dígitos después del +1.');
                        }
                        
                        // Verificar que solo tenga números después del +1
                        $digitsOnly = substr($cleaned, 2); // Remover +1
                        if (!ctype_digit($digitsOnly)) {
                            $fail('El número de teléfono solo puede contener dígitos después del +1.');
                        }
                    }
                }
            ],
            'password' => 'required|string|min:6',
            'role' => 'required|in:company_admin,manager,sales,guest,project_manager,crew',
        ]);

        $password = $request->password;

        $user = Team::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'user_id' => auth()->id(),
        ]);

        $loginUrl = url('/login');
        $user->notify(new UserCredentialsNotification($user->email, $password, $loginUrl));

        return redirect()->route('teams.index')->with('success', 'Team member created successfully. An email has been sent with the credentials.');
    }
    
    public function edit(Team $team)
    {
        return view('manageTeam.edit', compact('team'));
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:team,email,' . $team->id,
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\-\+\(\)]+$/', // ✅ Misma validación
            'role' => 'required|in:company_admin,manager,sales,guest,project_manager,crew',
        ]);
    
        $team->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone, // ✅ CORREGIDO: usa $request->phone
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
        ]);
    
        if ($request->filled('password')) {
            $team->password = Hash::make($request->password);
            $team->save();
        }
    
        return redirect()->route('teams.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team member deleted successfully.');
    }
}