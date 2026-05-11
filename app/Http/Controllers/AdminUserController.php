<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobRequest;
use App\Models\Emergencies;
use App\Models\Subcontractors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Mail\UserRejectedMail;
use App\Mail\UserApprovedMail;
use Illuminate\Support\Facades\Mail;

class AdminUserController extends Controller
{
    

// ==============================
// 🔹 Módulo: Admin Profile
// ==============================

public function index()
{
    // ==============================
    // 🔹 CONTRACTORS (SOLO APROBADOS)
    // ==============================
    $contractors = User::where('is_admin', false)
                        ->whereNotNull('approved_at')
                        ->where('is_active', true)
                        ->count();

    // ==============================
    // 🔹 PENDIENTES
    // ==============================
    $pendingUsers = User::where('is_admin', false)
                        ->whereNull('approved_at')
                        ->count();

    // ==============================
    // 🔹 SUBCONTRACTORS
    // ==============================
    $subcontractors = Subcontractors::count();

    // ==============================
    // 🔹 OFERTAS SIN CREW
    // ==============================
    $jobsUnassigned = JobRequest::where(function ($q) {
                            $q->whereNull('crew_id')->orWhere('crew_id', 0);
                        })->count();

    $emergUnassigned = Emergencies::where(function ($q) {
                            $q->whereNull('crew_id')->orWhere('crew_id', 0);
                        })->count();

    $offersUnassigned = $jobsUnassigned + $emergUnassigned;

    // ==============================
    // 🔹 OFERTAS CON CREW
    // ==============================
    $jobsAssigned = JobRequest::whereNotNull('crew_id')
                                ->where('crew_id', '!=', 0)
                                ->count();

    $emergAssigned = Emergencies::whereNotNull('crew_id')
                                ->where('crew_id', '!=', 0)
                                ->count();

    $offersAssigned = $jobsAssigned + $emergAssigned;

    // ==============================
    // 🔹 CRECIMIENTO ÚLTIMOS 30 DÍAS
    // ==============================

    $contractorsLastMonth = User::where('is_admin', false)
                                ->whereNotNull('approved_at')
                                ->where('is_active', true)
                                ->where('created_at', '>=', now()->subDays(30))
                                ->count();

    $subcontractorsLastMonth = Subcontractors::where('created_at', '>=', now()->subDays(30))
                                            ->count();

    $jobsLastMonth = JobRequest::where('created_at', '>=', now()->subDays(30))->count();
    $emergLastMonth = Emergencies::where('created_at', '>=', now()->subDays(30))->count();

    $offersLastMonth = $jobsLastMonth + $emergLastMonth;

    // ==============================
    // 🔹 CÁLCULO CRECIMIENTO
    // ==============================
    $growthContractors = $this->calculateGrowthRate($contractors, $contractorsLastMonth);
    $growthSubcontractors = $this->calculateGrowthRate($subcontractors, $subcontractorsLastMonth);
    $growthOffers = $this->calculateGrowthRate(($offersAssigned + $offersUnassigned), $offersLastMonth);

    return view('admin.users.index', compact(
        'contractors',
        'subcontractors',
        'offersUnassigned',
        'offersAssigned',
        'contractorsLastMonth',
        'subcontractorsLastMonth',
        'offersLastMonth',
        'growthContractors',
        'growthSubcontractors',
        'growthOffers',
        'pendingUsers'
    ));
}

private function calculateGrowthRate($total, $lastMonth)
{
    if ($total - $lastMonth <= 0) {
        return $lastMonth > 0 ? 100 : 0;
    }
    
    return round(($lastMonth / ($total - $lastMonth)) * 100);
}

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('superadmin.users.index')->with('success', 'Usuario eliminado');
    }




// ==============================
// 🔹 Módulo: Contractors
// ==============================
public function contractors(Request $request)
{
    $query = User::where('is_admin', false)
                ->whereNotNull('approved_at');

    // 🔍 Filtro búsqueda
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    // 🔍 Filtro estado
    if ($request->filled('status')) {

        if ($request->status === 'active') {
            $query->where('is_active', true);
        }

        if ($request->status === 'inactive') {
            $query->where('is_active', false);
        }

    }

    $users = $query->latest()->paginate(10);

    $contractors = $users->total();

    return view('admin.contractors.list', compact('users', 'contractors'));
}

    public function editContractors(User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'No puedes editar un administrador.');
        }

        $iconsByExtension = [
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'xls' => 'bi-file-earmark-spreadsheet text-success',
            'xlsx' => 'bi-file-earmark-spreadsheet text-success',
            'doc' => 'bi-file-earmark-word text-primary',
            'docx' => 'bi-file-earmark-word text-primary',
            'jpg' => 'bi-file-earmark-image text-info',
            'jpeg' => 'bi-file-earmark-image text-info',
            'png' => 'bi-file-earmark-image text-info',
            'default' => 'bi-file-earmark text-secondary',
        ];
    
        return view('admin.contractors.edit', compact('user', 'iconsByExtension'));
    }

    public function updateContractors(Request $request, User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'No puedes actualizar un administrador.');
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'years_experience' => 'nullable|integer|min:0',
            'language' => 'nullable|in:English,Spanish',
           
          
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'company_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx|max:5120'
        ]);
    
        if ($request->hasFile('profile_photo')) {
            // Eliminar foto anterior si existe en storage
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
        
            // Subir la nueva y guardar la ruta relativa en DB
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }
    
        // Manejar documentos de la compañía
        if ($request->hasFile('company_documents')) {
            $documents = $user->company_documents ?? [];
            foreach ($request->file('company_documents') as $file) {
                $documents[] = [
                    'file_name' => $file->store('company-documents', 'public'),
                    'original_name' => $file->getClientOriginalName(),
                    'uploaded_at' => now()
                ];
            }
            $validated['company_documents'] = $documents;
        }
    
    
    
        $user->update($validated);
    
        return redirect()->route('superadmin.users.contractors')->with('success', 'Contractor updated successfully.');
    }

    public function deleteContractorDocument(User $user, $index)
    {
        if ($user->is_admin) {
            return back()->with('error', 'No puedes eliminar documentos de un administrador.');
        }

        $docs = $user->company_documents ?? [];

        if (!isset($docs[$index])) {
            return back()->with('error', 'Documento no encontrado.');
        }

        // Elimina el archivo del disco
        Storage::disk('public')->delete($docs[$index]['file_name']);

        // Elimina del array y guarda
        array_splice($docs, $index, 1);
        $user->company_documents = $docs;
        $user->save();

        return back()->with('success', 'Documento eliminado correctamente.');
    }

    public function toggleActive(User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'You cannot change admin status.');
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back()->with('success', 'Contractor status updated.');
    }

    public function destroyContractors(User $user)
    {
        if ($user->is_admin) {
            return redirect()->back()->with('error', 'No se puede eliminar un administrador.');
        }

        $user->delete();

        return redirect()->route('superadmin.users.contractors')->with('success', 'Contractor successfully removed.');
    }




    public function pendingUsers(Request $request)
    {
        $query = User::pendingApproval();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10);

        return view('admin.users.pending', compact('users'));
    }

    public function approveUser(User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'You cannot approve an administrator.');
        }

        $user->approved_at = now();
        $user->approved_by = Auth::id();
        $user->rejection_reason = null;
        $user->is_active = true;
        $user->save();

        // 🔥 Enviar correo
        Mail::to($user->email)->send(new UserApprovedMail($user));

        return back()->with('success', 'User approved and successfully notified.');
    }
        

    public function rejectUser(Request $request, User $user)
    {
        if ($user->is_admin) {
            return back()->with('error', 'You cannot reject an administrator.');
        }

        $data = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        // Guardamos motivo (opcional si decides no borrar)
        $user->rejection_reason = $data['rejection_reason'] ?? null;

        // 🔥 Enviar correo con el motivo
        Mail::to($user->email)->send(
            new UserRejectedMail($user, $user->rejection_reason)
        );

        // Si quieres eliminarlo después de enviar el correo:
        $user->delete(); // o forceDelete()

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'User rejected and successfully notified.');
    }
        


}
