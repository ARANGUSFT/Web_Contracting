<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    protected $fillable = [
        'title',
        'start',
        'end',
        'type', // Ej: 'job' o 'emergency'
        'reference_id', // ID relacionado al job o emergencia
        'color',
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function invoice()
    {
        return $this->hasOne(Invoices::class);
    }

    public function jobRequest()
    {
        return $this->hasOne(JobRequest::class, 'id', 'reference_id');
    }

    public function emergency()
    {
        return $this->hasOne(Emergencies::class, 'id', 'reference_id');
    }


}
