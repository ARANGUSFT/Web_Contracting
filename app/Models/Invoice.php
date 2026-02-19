<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
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
    'status'
];




    public function payoutItems()
    {
        return $this->hasMany(InvoicePayoutItem::class);
    }


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

    public function crew()
    {
        return $this->belongsTo(Crew::class);
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

