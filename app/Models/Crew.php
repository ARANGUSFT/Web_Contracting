<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crew extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company',
        'email',
        'phone',
        'states',
        'is_active', // <-- Añadido
    ];

    protected $casts = [
        'states' => 'array',
        'is_active' => 'boolean', // <-- Añadido
    ];

    public function subcontractors()
    {
        return $this->belongsToMany(Subcontractors::class, 'crew_subcontractor', 'crew_id', 'subcontractor_id')->withTimestamps();
    }
    
}
