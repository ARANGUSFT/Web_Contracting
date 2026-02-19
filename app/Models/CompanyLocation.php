<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyLocation extends Model
{
    protected $fillable = ['user_id', 'state', 'city'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Precios de items para esta empresa + estado
    public function itemPrices()
    {
        return $this->hasMany(ItemPrice::class);
    }

    // Acceso indirecto a items (opcional)
    public function items()
    {
        return $this->belongsToMany(
            Item::class,
            'item_prices'
        )->withPivot(['price', 'is_active'])->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
