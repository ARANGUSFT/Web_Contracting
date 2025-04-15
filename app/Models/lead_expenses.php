<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lead_expenses extends Model
{
    use HasFactory;

    protected $table = 'lead_expenses';
    
    protected $fillable = [
        'lead_id',
        'expense_date',
        'material', // ahora representa el costo del material
        'labor_cost',
        'commission_percentage',
        'permit',
        'supplement',
        'other_expenses',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'material' => 'decimal:2',
        'labor_cost' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'supplement' => 'decimal:2',
        'other_expenses' => 'decimal:2',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function getTotalExpenseAttribute()
    {
        return collect([
            $this->material,
            $this->labor_cost,
            $this->supplement,
            $this->other_expenses,
        ])->filter()->sum();
    }
}
