<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PhotoShare extends Model
{
    protected $table = 'photo_shares';

    // Solo guardamos lo necesario para un enlace público fijo por proyecto
    protected $fillable = [
        'type',       // 'job_request' | 'emergency'
        'model_id',   // id del JobRequest o Emergencies
        'token',      // token público único
        'created_by', // opcional: id del admin que lo creó
    ];

    /**
     * Scope: filtra por destino (tipo + id del modelo).
     */
    public function scopeFor(Builder $q, string $type, int $modelId): Builder
    {
        return $q->where('type', $type)->where('model_id', $modelId);
    }

    /**
     * Accessor: URL pública lista para usar en la vista.
     */
    public function getPublicUrlAttribute(): string
    {
        return route('photos.public', ['token' => $this->token]);
    }

    /**
     * Crea (o devuelve) el enlace público de un proyecto.
     * Reutiliza el existente gracias al índice UNIQUE (type, model_id).
     */
    public static function ensure(string $type, int $modelId, ?int $createdBy = null): self
    {
        return static::firstOrCreate(
            ['type' => $type, 'model_id' => $modelId],
            ['token' => Str::random(40), 'created_by' => $createdBy]
        );
    }

    /**
     * Regenera el token (por si quieres crear un nuevo link y “anular” el anterior).
     */
    public static function regenerate(string $type, int $modelId, ?int $createdBy = null): self
    {
        $share = static::firstOrNew(['type' => $type, 'model_id' => $modelId]);
        $share->token = Str::random(40);
        $share->created_by = $createdBy;
        $share->save();

        return $share;
    }
}
