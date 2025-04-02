<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadFinanza extends Model
{
    use HasFactory;

    protected $fillable = ['lead_id', 'user_id', 'date', 'amount', 'method', 'check_number', 'notes'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
