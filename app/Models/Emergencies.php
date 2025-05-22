<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencies extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_submitted',
        'type_of_supplement',
        'company_name',
        'company_contact_email',
        'job_number_name',
        'job_address',
        'job_address_line2',
        'job_city',
        'job_state',
        'job_zip_code',
        'terms_conditions',
        'requirements',
        'aerial_measurement_path',
        'contract_upload_path',
        'file_picture_upload_path',
    ];

    protected $casts = [
        'aerial_measurement_path' => 'array',
        'contract_upload_path' => 'array',
        'file_picture_upload_path' => 'array',
    ];
    
}
