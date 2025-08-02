<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id', 
        'crew_id',


        // General Info
        'install_date_requested',
        'company_name',
        'company_rep',
        'company_rep_phone',
        'company_rep_email',

        // Customer Info
        'customer_first_name',
        'customer_last_name',
        'customer_phone_number',

        // Job Address
        'job_number_name',
        'job_address_street_address',
        'job_address_street_address_line_2',
        'job_address_city',
        'job_address_state',
        'job_address_zip_code',

        // Material Ordered
        'material_roof_loaded',
        'starter_bundles_ordered',
        'hip_and_ridge_ordered',
        'field_shingle_bundles_ordered',
        'modified_bitumen_cap_rolls_ordered',
        'delivery_date',

        // Inspections and Replacements
        'mid_roof_inspection',
        'siding_being_replaced',
        'asphalt_shingle_layers_to_remove',
        're_deck',
        'skylights_replace',
        'gutter_remove',
        'gutter_detached_and_reset',
        'satellite_remove',
        'satellite_goes_in_the_trash',
        'open_soffit_ceiling',
        'detached_garage_roof',
        'detached_shed_roof',
            'status',


        // Additional
        'special_instructions',
        'material_verification',
        'stop_work_request',
        'documentationattachment',

        // Files (JSON arrays)
        'aerial_measurement',
        'material_order',
        'file_upload',
    ];

    protected $casts = [
        'install_date_requested'    => 'date',
        'aerial_measurement' => 'array',
        'material_order'     => 'array',
        'file_upload'        => 'array',
    ];

    public function teamMembers()
    {
        return $this->belongsToMany(Team::class, 'job_request_team', 'job_request_id', 'team_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function crew()
    {
        return $this->belongsTo(Crew::class);
    }

   public function fotos()
    {
        return $this->morphMany(Foto::class, 'imageable');
    }


    public function notes()
    {
        return $this->morphMany(EventNote::class, 'noteable');
    }

     // ✅ Devuelve todos los estados posibles
    public static function availableStatuses(): array
    {
        return ['pendiente', 'en_proceso', 'completado'];
    }

    // ✅ Avanza al siguiente estado
    public function advanceStatus(): void
    {
        $flow = self::availableStatuses();
        $index = array_search($this->status, $flow);
        if ($index !== false && $index < count($flow) - 1) {
            $this->status = $flow[$index + 1];
            $this->save();
        }
    }

    // ✅ Retrocede al estado anterior
    public function rollbackStatus(): void
    {
        $flow = self::availableStatuses();
        $index = array_search($this->status, $flow);
        if ($index !== false && $index > 0) {
            $this->status = $flow[$index - 1];
            $this->save();
        }
    }



}
