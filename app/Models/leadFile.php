<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFile extends Model
{
     protected $fillable = [
        'lead_id',
        'user_id',
        'team_id',
        'folder_id',
        'file_path',
    ];

    public function folder()
    {
        return $this->belongsTo(LeadFolder::class);
    }
    
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class); // Para usuarios normales
    }

    public function team()
    {
        return $this->belongsTo(Team::class); // Para vendedores
    }
}