<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lead_approvals extends Model
{
    use HasFactory;

    protected $table = 'lead_approvals'; // necesario si el nombre de clase no coincide

    protected $fillable = [
        'lead_id',
        'company_name',
        'company_representative',
        'company_phone',
        'lead_name',
        'lead_address',
        'lead_phone',
        'installation_date',
        'extra_info',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
