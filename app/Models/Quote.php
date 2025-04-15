<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'user_id',
        'team_id',
        'sq',
        'material_cost_per_sq',
        'labor_cost_per_sq',
        'other_costs',
        'material_total',
        'labor_total',
        'profit',
        'quote_total',
        'percentage',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
