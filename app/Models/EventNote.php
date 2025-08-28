<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EventNote extends Model
{
    protected $fillable = ['noteable_type', 'noteable_id', 'user_id', 'subcontractor_id', 'content'];


   public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subcontractor()
    {
        return $this->belongsTo(Subcontractors::class);
    }

    public function noteable()
    {
        return $this->morphTo();
    }

     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}

