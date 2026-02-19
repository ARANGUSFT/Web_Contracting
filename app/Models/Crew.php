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
        'has_trailer',   // ✅ NUEVO
        'is_active', // <-- Añadido
    ];

    protected $casts = [
        'states' => 'array',
        'has_trailer' => 'boolean', // ✅ NUEVO
        'is_active' => 'boolean', // <-- Añadido
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


    public function subcontractors()
    {
        return $this->belongsToMany(Subcontractors::class, 'crew_subcontractor', 'crew_id', 'subcontractor_id')->withTimestamps();
    }

    public function emergencies()
    {
        return $this->hasMany(Emergencies::class);
    }
    
    public function jobRequests()
    {
        return $this->hasMany(JobRequest::class);
    }
    
     
}
