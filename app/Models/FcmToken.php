<?php
// app/Models/FcmToken.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $fillable = ['subcontractor_id', 'token'];

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractors::class);
    }
}