<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_location_id',
        'crew_id',
        'customer_email',
        'bill_to',
        'address',
        'memo',
        'notes',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'invoiceable_type',   // ← relación polimórfica
        'invoiceable_id',     // ← relación polimórfica
        'subcontractor_paid',
        'subcontractor_paid_at',
    ];

    protected $casts = [
        'invoice_date'          => 'date',
        'due_date'              => 'date',
        'subtotal'              => 'decimal:2',
        'tax'                   => 'decimal:2',
        'total'                 => 'decimal:2',
        'subcontractor_paid'    => 'boolean',
        'subcontractor_paid_at' => 'datetime',
    ];

    // ── Relación polimórfica — job / emergency / repair ────────
    public function invoiceable()
    {
        return $this->morphTo();
    }

    // ── Helpers para leer el tipo legible ─────────────────────
    public function getInvoiceableTypeLabelAttribute(): string
    {
        return match($this->invoiceable_type) {
            \App\Models\JobRequest::class   => 'Job Request',
            \App\Models\Emergencies::class  => 'Emergency',
            \App\Models\RepairTicket::class => 'Repair Ticket',
            default                         => '—',
        };
    }

    // ── Payout items ───────────────────────────────────────────
    public function payoutItems()
    {
        return $this->hasMany(InvoicePayoutItem::class);
    }

    // ── Location ───────────────────────────────────────────────
    public function companyLocation()
    {
        return $this->belongsTo(CompanyLocation::class);
    }

    // ── Usuario (empresa) ──────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Crew ───────────────────────────────────────────────────
    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

    // ── Items ──────────────────────────────────────────────────
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // ── Attachments ────────────────────────────────────────────
    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }
}