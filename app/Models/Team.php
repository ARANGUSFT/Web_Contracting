<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Team extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'team'; // Asegúrate de que coincida con tu BD

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active',
    'user_id'];

    protected $hidden = ['password'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accesor para obtener el estado en texto
    public function getStatusAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    // Relación con los Leads (un vendedor tiene muchos leads asignados)
    public function leads()
    {
        return $this->hasMany(Lead::class, 'team_id');
    }

        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

        public function jobRequests()
    {
        return $this->belongsToMany(JobRequest::class, 'job_request_team', 'team_id', 'job_request_id');
    }

    // ✅ Correcto:
    public function emergencies()
    {
        return $this->belongsToMany(Emergencies::class, 'emergency_team', 'team_id', 'emergency_id');
    }




}
