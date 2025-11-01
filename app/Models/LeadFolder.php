<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadFolder extends Model
{
    protected $fillable = ['lead_id', 'name'];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function files()
    {
        return $this->hasMany(leadFile::class, 'folder_id');
    }
}
