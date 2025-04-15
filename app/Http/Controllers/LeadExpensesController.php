<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadExpensesController extends Controller
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

        // Validación actualizada
        $rules = [
            'expenses' => 'nullable|array',
            'expenses.*.expense_date' => 'required|date',
            'expenses.*.material' => 'nullable|numeric|min:0',
            'expenses.*.labor_cost' => 'nullable|numeric|min:0',
            'expenses.*.commission_percentage' => 'nullable|numeric|min:0|max:100',
            'expenses.*.permit' => 'nullable|string|max:255',
            'expenses.*.supplement' => 'nullable|numeric|min:0',
            'expenses.*.other_expenses' => 'nullable|numeric|min:0',
        ];

        $request->validate($rules);

        // Eliminar gastos anteriores
        $lead->expenses()->delete();

        // Guardar gastos nuevos
        foreach ($request->input('expenses', []) as $data) {
            $lead->expenses()->create([
                'expense_date' => $data['expense_date'],
                'material' => $data['material'] ?? null,
                'labor_cost' => $data['labor_cost'] ?? null,
                'commission_percentage' => $data['commission_percentage'] ?? null,
                'permit' => $data['permit'] ?? null,
                'supplement' => $data['supplement'] ?? null,
                'other_expenses' => $data['other_expenses'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Expense data updated successfully.');
    }

    public function destroy(Lead $lead, $expenseId)
    {
        $user = Auth::user();
        if ($lead->user_id !== $user->id && $lead->team_id !== $user->id) {
            abort(403);
        }
    
        $expense = $lead->expenses()->findOrFail($expenseId);
        $expense->delete();
    
        return response()->json(['success' => true]);
    }
    

}
