<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subcontractors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    

// ==============================
// 🔹 Módulo: Admin Profile
// ==============================

    public function index()
    {
        $contractors = User::where('is_admin', false)->count();
        $subcontractors = Subcontractors::count();
        // $offers = Quote::count(); 
        return view('admin.users.index', compact('contractors', 'subcontractors'));
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

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado');
    }




// ==============================
// 🔹 Módulo: Contractors
// ==============================
    public function contractors(Request $request)
    {
        $query = User::where('is_admin', false);
        
        // Aplicar filtros
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
        
        if ($request->filled('status')) {
            $status = $request->input('status') === 'active';
            $query->where('is_active', $status);
        }
        
        // Filtro por estados (usando states_you_can_work que es un array)
        if ($request->filled('state')) {
            $query->whereJsonContains('states_you_can_work', $request->input('state'));
        }
        
        // Filtro por años de experiencia
        if ($request->filled('experience')) {
            $query->where('years_experience', '>=', $request->input('experience'));
        }
        
        // Filtro por tipo de techo (residencial/comercial)
        if ($request->filled('roof_type')) {
            $roofType = $request->input('roof_type');
            if ($roofType === 'residential') {
                $query->whereNotNull('residential_roof_types');
            } elseif ($roofType === 'commercial') {
                $query->whereNotNull('commercial_roof_types');
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
           
            'admin_notes' => 'nullable|string',
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
                    'file_name' => $file->store('company-documents'),
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


}
