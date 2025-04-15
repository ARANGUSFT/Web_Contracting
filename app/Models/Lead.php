<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'estado', 'company_name', 'cross_reference', 
        'job_category', 'work_type', 'job_trades', 'lead_source', 'phone', 
        'phone_ext', 'phone_type', 'email', 'street', 'suite', 'city', 'state', 
        'zip', 'country', 'mailing_address', 'streetmailing', 'suitemailing', 
        'citymailing', 'zipmailing', 'billing_address', 'streetbilling', 
        'suitebilling', 'citybilling', 'statebilling', 'zipbilling', 
        'insurance_company', 'adjuster_phone_type', 'damage_location', 
        'date_loss', 'claim_number', 'adjuster_phone', 'adjuster_ext', 
        'adjuster_fax', 'adjuster_email', 'notas', 'id_padre', 'location_photo',
        'user_id', 'contract_value', 'last_touched_at'
    ];

    protected $casts = [
        'location_photo' => 'array',
        'date_loss' => 'date',
        'last_touched_at' => 'datetime', 

    ];

    

    
    public function statusText()
    {
        $statuses = [
            1 => 'Lead',
            2 => 'Prospect',
            3 => 'Approved',
            4 => 'Completed',
            5 => 'Invoiced',
            6 => 'Closed'
        ];

        return $statuses[$this->estado] ?? 'Unknown';
    }

    

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    // ✅ Relación con mensajes del chat
    public function messages()
    {
        return $this->hasMany(LeadMessage::class, 'lead_id');
    }

    // ✅ Relación con imágenes subidas
    public function images()
    {
        return $this->hasMany(LeadImage::class, 'lead_id');
    }

    public function files()
    {
        return $this->hasMany(leadFile::class);
    }

    public function finanzas()
    {
        return $this->hasMany(LeadFinanza::class);
    }


        public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(lead_expenses::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    




}
