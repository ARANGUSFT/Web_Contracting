<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    use HasFactory;

    protected $fillable = ['subcontractor_id', 'expires_at', 'file', 'notes'];

    protected $casts = [
        'file' => 'array', // convierte JSON en array automáticamente
    ];

    // Relación inversa
    public function subcontractor()
    {
        return $this->belongsTo(Subcontractors::class);
    }
}
