<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
        protected $fillable = [
    'user_id',
    'company_location_id',
    'customer_email',
    'bill_to',
    'memo',
    'notes',
    'invoice_number',
    'invoice_date',
    'due_date',
    'subtotal',
    'tax',
    'total',
    'status'
];



      // ✅ RELACIÓN CON LOCATION
    public function companyLocation()
    {
        return $this->belongsTo(CompanyLocation::class);
    }

     // Relación con usuario (empresa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function attachments()
    {
        return $this->hasMany(InvoiceAttachment::class);
    }


}

