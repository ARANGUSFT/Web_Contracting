<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcontractors extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'name',
        'last_name',
        'email',
        'phone',
        
        'residential_roof_types',
        'commercial_roof_types',
        'states_you_can_work',
        'all_states',

        'state',
        'password',
        'is_active',
    ];

    protected $casts = [
        'residential_roof_types' => 'array',
        'commercial_roof_types' => 'array',
        'states_you_can_work' => 'array',
        'all_states' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function crews()
    {
        return $this->belongsToMany(Crew::class, 'crew_subcontractor');
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'subcontractor_id');
    }
}
