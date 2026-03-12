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
            'expenses.*.amount' => 'required',
            'expenses.*.notes' => 'nullable|string',
        ]);

        foreach ($request->expenses as $expense) {
            $amount = str_replace(',', '', $expense['amount']);

            LeadExpenses::create([
                'lead_id' => $request->lead_id,
                'expense_date' => $expense['expense_date'],
                'type' => $expense['type'],
                'amount' => is_numeric($amount) ? $amount : 0,
                'notes' => $expense['notes'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Expenses saved successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $expense = LeadExpenses::findOrFail($id);
        $expense->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Expense deleted successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }
}