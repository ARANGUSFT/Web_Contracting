<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteController extends Controller
{
    public function index()
    {
        $quotes = Quote::with(['lead', 'user', 'team'])->get();
        return response()->json($quotes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'sq' => 'required|numeric',
            'material_cost_per_sq' => 'required|numeric',
            'labor_cost_per_sq' => 'required|numeric',
            'other_costs' => 'nullable|numeric',
            'percentage' => 'required|numeric',
        ]);

        $userId = auth('web')->check() ? auth('web')->id() : null;
        $teamId = auth('team')->check() ? auth('team')->id() : null;

        if (!$userId && !$teamId) {
            return back()->withErrors(['error' => 'Not authenticated']);
        }

        $material_total = $validated['sq'] * $validated['material_cost_per_sq'];
        $labor_total = $validated['sq'] * $validated['labor_cost_per_sq'];
        $base_total = $material_total + $labor_total + ($validated['other_costs'] ?? 0);
        $profit = ($validated['percentage'] / 100) * $base_total;
        $quote_total = $base_total + $profit;

        $quote = Quote::create([
            'lead_id' => $validated['lead_id'],
            'user_id' => $userId,
            'team_id' => $teamId,
            'sq' => $validated['sq'],
            'material_cost_per_sq' => $validated['material_cost_per_sq'],
            'labor_cost_per_sq' => $validated['labor_cost_per_sq'],
            'other_costs' => $validated['other_costs'] ?? 0,
            'material_total' => $material_total,
            'labor_total' => $labor_total,
            'profit' => $profit,
            'quote_total' => $quote_total,
            'percentage' => $validated['percentage'],
        ]);

        return redirect()->back()->with('success', 'Quote saved successfully.');
    }


    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        $quote->delete();
    
        return redirect()->back()->with('success', 'Quotation removed correctly.');
    }
    
}
