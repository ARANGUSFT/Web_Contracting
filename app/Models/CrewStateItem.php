<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrewStateItem extends Model
{
    protected $fillable = [
        'crew_id',
        'state',
        'name',
        'description',
        'price',
    ];

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }
}

