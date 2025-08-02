<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emergencies extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'user_id', 
        'crew_id',

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
            'status',

        'aerial_measurement_path',
        'contract_upload_path',
        'file_picture_upload_path',

    ];

    protected $casts = [
        'date_submitted'            => 'date',
        'aerial_measurement_path' => 'array',
        'contract_upload_path' => 'array',
        'file_picture_upload_path' => 'array',
    ];

    // app/Models/Emergency.php

    public function teamMembers()
    {
        return $this->belongsToMany(Team::class, 'emergency_team', 'emergency_id', 'team_id');
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
