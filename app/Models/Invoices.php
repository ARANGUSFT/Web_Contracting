<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $fillable = ['calendar_id', 'paid', 'due'];

    public function calendar()
    {
        return $this->belongsTo(\App\Models\Calendar::class);
    }

    // si ya tienes historial: relación con pagos
    public function payments()
    {
        return $this->hasMany(\App\Models\InvoicePayment::class, 'invoice_id');
    }
}
