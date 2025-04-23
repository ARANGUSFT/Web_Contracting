<?php

namespace App\Http\Controllers;

use App\Models\LeadExpenses;
use Illuminate\Http\Request;

class LeadExpensesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'expenses' => 'required|array',
            'expenses.*.expense_date' => 'required|date',
            'expenses.*.type' => 'required|in:material,labor,commission,permit,supplement,other',
            'expenses.*.amount' => 'required|numeric|min:0',
        ]);

        foreach ($request->expenses as $expense) {
            LeadExpenses::create([
                'lead_id' => $request->lead_id,
                'expense_date' => $expense['expense_date'],
                'type' => $expense['type'],
                'amount' => $expense['amount'],
            ]);
        }

        return redirect()->back()->with('success', 'Expenses saved successfully.');
    }

    

    public function destroy($id)
    {
        $expense = LeadExpenses::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Gasto eliminado correctamente.');
    }

}
