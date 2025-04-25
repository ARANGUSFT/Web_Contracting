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
        $teams = Team::all();
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
            'password' => 'required|string|min:6',
            'role' => 'required|in:company_admin,manager,sales,guest,project_manager,crew',
        ]);
    
        $password = $request->password; // Guardamos la contraseña sin cifrar para enviarla
    
        $user = Team::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'user_id' => auth()->id(), // ✅ Asigna el admin que lo creó
        ]);
        
    
        // URL de acceso (ajusta según sea necesario)
        $loginUrl = url('/login');
    
        // Enviar notificación con credenciales
        $user->notify(new UserCredentialsNotification($user->email, $password, $loginUrl));
    
        return redirect()->route('teams.index')->with('success', 'Team member created successfully. Se ha enviado un correo con las credenciales.');
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
            'role' => 'required|in:company_admin,manager,sales,guest,project_manager,crew',
        ]);
    
        $team->update([
            'name' => $request->name,
            'email' => $request->email,
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
