<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'install_date_requested', 'company_name', 'company_rep', 'company_rep_phone', 'company_rep_email',
        'customer_first_name', 'customer_last_name', 'customer_phone_number',
        'job_number_name', 'job_address_street_address', 'job_address_street_address_line_2',
        'job_address_city', 'job_address_state', 'job_address_zip_code',
        'material_roof_loaded', 'starter_bundles_ordered', 'hip_and_ridge_ordered',
        'field_shingle_bundles_ordered', 'modified_bitumen_cap_rolls_ordered', 'delivery_date',
        'mid_roof_inspection', 'siding_being_replaced', 'asphalt_shingle_layers_to_remove',
        're_deck', 'skylights_replace', 'gutter_remove', 'gutter_detached_and_reset',
        'satellite_remove', 'satellite_goes_in_the_trash', 'open_soffit_ceiling',
        'detached_garage_roof', 'detached_shed_roof',
        'special_instructions', 'material_verification', 'stop_work_request', 'documentationattachment',
        'aerial_measurement', 'material_order', 'file_upload',
    ];
    
}
