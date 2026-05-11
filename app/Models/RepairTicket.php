<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EventNote;

class RepairTicket extends Model
{
    protected $fillable = [
        'user_id',
        'crew_id',
        'reference_type',
        'reference_id',
        'repair_date',
        'description',
        'status',
        'sequence_number',   // ← agrega esta línea

          // ── Pago ──────────────────────────────────────
        'amount',
        'payment_status',
        'payment_date',
        'payment_receipt_path',

    ];

    protected $casts = [
 
        'repair_date' => 'date',
    ];

    public function jobRequest()
    {
        return $this->belongsTo(JobRequest::class, 'reference_id');
    }

    public function emergency()
    {
        return $this->belongsTo(Emergencies::class, 'reference_id');
    }

    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    public function notes()
    {
        return $this->morphMany(EventNote::class, 'noteable');
    }

    public function reference()
    {
        return $this->reference_type === 'job'
            ? $this->jobRequest
            : $this->emergency;
    }

    public function getReferenceNumberAttribute(): string
    {
        return $this->reference()?->job_number_name ?? '—';
    }




    // Todas las fotos
    public function fotos(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(\App\Models\Foto::class, 'imageable');
    }

    // Solo fotos del admin (daño original)
    public function fotosAdmin(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(\App\Models\Foto::class, 'imageable')
                    ->where('source', 'admin');
    }

    // Solo fotos del crew (trabajo realizado)
    public function fotosCrew(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(\App\Models\Foto::class, 'imageable')
                    ->where('source', 'crew');
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }
}