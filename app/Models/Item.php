<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_location_id',
        'name',
        'description',
        'price',
        'is_active',
    ];

    /**
     * Relación: Item pertenece a un estado (location)
     */
    public function location()
    {
        return $this->belongsTo(CompanyLocation::class, 'company_location_id');
    }

    public function itemsJson(CompanyLocation $location)
    {
        return response()->json(
            $location->items()->select('id','name','description','price')->get()
        );
    }

}
