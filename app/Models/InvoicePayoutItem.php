<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoicePayoutItem extends Model
{
    protected $fillable = ['invoice_id', 'description', 'price', 'quantity', 'total'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
