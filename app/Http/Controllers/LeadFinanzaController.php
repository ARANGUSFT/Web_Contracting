<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadFinanzaController extends Controller
{
    private function getCurrentUser()
    {
        return Auth::guard('web')->check() ? Auth::guard('web')->user() : Auth::guard('team')->user();
    }

    public function update(Request $request, $leadId)
    {
        $user = $this->getCurrentUser();
        $lead = Lead::findOrFail($leadId);

        if ($lead->user_id !== $user->id && $lead->team_id !== $user->id) {
            abort(403);
        }

        // Reglas básicas
        $rules = [
            'contract_value' => 'required|numeric|min:0',
            'finanzas' => 'nullable|array',
            'finanzas.*.date' => 'required|date',
            'finanzas.*.amount' => 'required|numeric|min:0',
            'finanzas.*.method' => 'nullable|string|max:255',
            'finanzas.*.check_number' => 'nullable|string|max:255',
            'finanzas.*.notes' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        // Validación condicional por método "Check"
        foreach ($request->input('finanzas', []) as $i => $data) {
            if (strtolower($data['method'] ?? '') === 'check' && empty($data['check_number'])) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(["finanzas.$i.check_number" => "Check number is required when payment method is 'Check'."]);
            }
        }

        // Guardar valor del contrato
        $lead->contract_value = $request->input('contract_value');
        $lead->save();

        // Borrar aportes anteriores
        $lead->finanzas()->delete();

        // Guardar aportes nuevos
        foreach ($request->input('finanzas', []) as $data) {
            $lead->finanzas()->create([
                'user_id' => $user->id,
                'date' => $data['date'],
                'amount' => $data['amount'],
                'method' => $data['method'] ?? null,
                'check_number' => $data['check_number'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Financial data updated successfully.');
    }
}
