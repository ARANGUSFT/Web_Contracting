<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'uploaded_by_type',
        'uploaded_by_id',
        'image_path',
        'user_id',
        'file_name',     // ← NUEVO
        'file_size',     // ← NUEVO  
        'file_hash',     // ← NUEVO
        'mime_type',     // ← NUEVO
    ];

    // Relaciones existentes...
    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}