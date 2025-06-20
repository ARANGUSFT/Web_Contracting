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
        'state',
        'password',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    
}
