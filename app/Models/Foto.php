<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $table    = 'fotos';
    protected $fillable = ['url', 'source', 'imageable_id', 'imageable_type'];

    public function imageable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeAdmin($query)
    {
        return $query->where('source', 'admin');
    }

    public function scopeCrew($query)
    {
        return $query->where('source', 'crew');
    }

    public function scopeForModel($query, string $type, int $id)
    {
        return $query->where('imageable_type', $type)
                     ->where('imageable_id', $id);
    }
}