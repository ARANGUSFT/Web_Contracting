<?php
// app/Http/Controllers/FcmController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FcmToken;

class FcmController extends Controller
{
    // Guardar o actualizar token del dispositivo
    public function store(Request $request)
    {
        $request->validate([
            'subcontractor_id' => 'required|integer|exists:subcontractors,id',
            'token'            => 'required|string',
        ]);

        FcmToken::updateOrCreate(
            ['subcontractor_id' => $request->subcontractor_id],
            ['token'            => $request->token]
        );

        return response()->json(['success' => true]);
    }

    // Eliminar token al hacer logout
    public function destroy(Request $request)
    {
        $request->validate([
            'subcontractor_id' => 'required|integer',
        ]);

        FcmToken::where('subcontractor_id', $request->subcontractor_id)->delete();

        return response()->json(['success' => true]);
    }
}