<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceAttachment extends Model
{
    protected $fillable = [
        'invoice_id',
        'original_name',
        'file_path',
        'mime_type',
        'size'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
