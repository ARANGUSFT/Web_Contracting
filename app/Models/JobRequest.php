<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JobRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','crew_id',

        // General Info
        'install_date_requested','company_name','company_rep','company_rep_phone','company_rep_email',

        // Customer Info
        'customer_first_name','customer_last_name','customer_phone_number',

        // Job Address
        'job_number_name','job_address_street_address','job_address_street_address_line_2',
        'job_address_city','job_address_state','job_address_zip_code',

        // Material Ordered
        'material_roof_loaded','starter_bundles_ordered','hip_and_ridge_ordered',
        'field_shingle_bundles_ordered','modified_bitumen_cap_rolls_ordered','delivery_date',

        // Inspections and Replacements
        'mid_roof_inspection','siding_being_replaced','asphalt_shingle_layers_to_remove','re_deck',
        'skylights_replace','gutter_remove','gutter_detached_and_reset','satellite_remove',
        'satellite_goes_in_the_trash','open_soffit_ceiling','detached_garage_roof','detached_shed_roof',
        'status',

        // Additional
        'special_instructions','material_verification','stop_work_request','documentationattachment',

        // Files (JSON arrays)
        'aerial_measurement','material_order','file_upload',

        'amount',
        'payment_receipt_path', 
        'payment_date',
        'payment_status',
    ];

    protected $casts = [
        'install_date_requested' => 'date',
        'delivery_date'          => 'date',
        'aerial_measurement'     => 'array',
        'material_order'         => 'array',
        'file_upload'            => 'array',
    ];

    protected $appends = [
        'aerial_measurement_urls',
        'material_order_urls',
        'file_upload_urls',
    ];


    
    public function teams()
    {
        return $this->belongsToMany(
            \App\Models\Team::class,   // modelo Team (tabla "team")
            'job_request_team',        // tabla pivot
            'job_request_id',          // FK hacia job_requests
            'team_id'                  // FK hacia team
        );
    }
    

    public function repairTickets()
    {
        return $this->hasMany(RepairTicket::class, 'reference_id')
                    ->where('reference_type', 'job');
    }

    /* ===================== Relaciones ===================== */
    public function teamMembers()
    {
        return $this->belongsToMany(Team::class, 'job_request_team', 'job_request_id', 'team_id');
    }
    public function user() { return $this->belongsTo(User::class); }
    public function crew() { return $this->belongsTo(Crew::class); }
    public function fotos(){ return $this->morphMany(Foto::class, 'imageable'); }
    public function notes(){ return $this->morphMany(EventNote::class, 'noteable'); }

    /* ================== Normalización al guardar ================== */
    // Acepta string/CSV/JSON/array y lo deja como array -> el cast lo serializa a JSON
    protected function normalizeToArray($value): array
    {
        if (is_null($value) || $value === '') return [];
        if (is_array($value)) return array_values(array_filter($value));

        if (is_string($value)) {
            // CSV simple: "path1,path2"
            if (strpos($value, ',') !== false && json_decode($value, true) === null) {
                $items = array_map('trim', explode(',', $value));
                return array_values(array_filter($items));
            }
            // JSON válido
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return array_values(array_filter($decoded));
            }
            // Una sola ruta
            return [$value];
        }
        return [];
    }

    public function setAerialMeasurementAttribute($value): void
    {
        $this->attributes['aerial_measurement'] = json_encode($this->normalizeToArray($value));
    }
    public function setMaterialOrderAttribute($value): void
    {
        $this->attributes['material_order'] = json_encode($this->normalizeToArray($value));
    }
    public function setFileUploadAttribute($value): void
    {
        $this->attributes['file_upload'] = json_encode($this->normalizeToArray($value));
    }

    /* ================== URLs públicas (accessors) ================== */
    protected function pathsToUrls($value): array
    {
        if (empty($value)) return [];
        $paths = is_array($value) ? $value : [$value];

        return array_values(array_filter(array_map(function ($p) {
            if (!$p) return null;
            if (is_string($p) && preg_match('#^https?://#i', $p)) return $p; // ya es URL
            return Storage::url($p); // si usas S3: Storage::disk('s3')->url($p)
        }, $paths)));
    }

    public function getAerialMeasurementUrlsAttribute(): array
    {
        return $this->pathsToUrls($this->aerial_measurement);
    }
    public function getMaterialOrderUrlsAttribute(): array
    {
        return $this->pathsToUrls($this->material_order);
    }
    public function getFileUploadUrlsAttribute(): array
    {
        return $this->pathsToUrls($this->file_upload);
    }

    /* ===================== Estados ===================== */
    public static function availableStatuses(): array
    {
          return ['pending', 'en_process', 'completed'];
    }
    public function advanceStatus(): void
    {
        $flow = self::availableStatuses();
        $i = array_search($this->status, $flow);
        if ($i !== false && $i < count($flow) - 1) { $this->status = $flow[$i + 1]; $this->save(); }
    }
    public function rollbackStatus(): void
    {
        $flow = self::availableStatuses();
        $i = array_search($this->status, $flow);
        if ($i !== false && $i > 0) { $this->status = $flow[$i - 1]; $this->save(); }
    }

    public function invoices()
    {
        return $this->morphMany(Invoice::class, 'invoiceable');
    }
}
