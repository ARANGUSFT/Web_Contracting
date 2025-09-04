<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class InvoicePayment extends Model
{
    protected $fillable = [
        'invoice_id','amount','paid_at','method','reference','note','attachments','created_by'
    ];

    protected $casts = [
        'paid_at'     => 'date',
        'attachments' => 'array',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoices::class);
    }

    // URLs públicas a adjuntos
    public function getAttachmentUrlsAttribute(): array
    {
        $files = $this->attachments ?? [];
        if (!is_array($files)) $files = [$files];

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;
            if (preg_match('#^https?://#i', $p)) return $p;
            return Storage::url($p); // disk 'public'
        }, $files)));
    }
}
