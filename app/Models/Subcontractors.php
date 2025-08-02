<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Subcontractors extends Authenticatable
{
    use HasApiTokens, HasFactory;

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
        return $this->belongsToMany(Crew::class, 'crew_subcontractor', 'subcontractor_id', 'crew_id')->withTimestamps();
    }

    public function insurances()
    {
        return $this->hasMany(Insurance::class, 'subcontractor_id');
    }

    public function notes()
{
    return $this->hasMany(EventNote::class, 'subcontractor_id');
}
}



