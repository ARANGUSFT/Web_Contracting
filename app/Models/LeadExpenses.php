<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadExpenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'expense_date',
        'type',
        'amount',
        'notes',
    ];
    
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
    
}
