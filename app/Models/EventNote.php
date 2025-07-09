<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventNote extends Model
{
    protected $fillable = ['content','user_id'];

    public function noteable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
