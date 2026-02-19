<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'global_price',
        'crew_price_with_trailer',
        'crew_price_without_trailer',
        'sort_order',
        'is_active',
        'category_id',
    ];

    protected $casts = [
        'global_price'               => 'decimal:2',
        'crew_price_with_trailer'    => 'decimal:2',
        'crew_price_without_trailer' => 'decimal:2',
        'is_active'                  => 'boolean',
    ];

    /**
     * Precios por ubicación / estado
     */
    public function prices()
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'category_id');
    }

    /**
     * Relación con locations vía item_prices
     */
    public function companyLocations()
    {
        return $this->belongsToMany(
            CompanyLocation::class,
            'item_prices'
        )->withPivot(['price', 'is_active'])
         ->withTimestamps();
    }

    /**
     * 🔑 Precio efectivo cliente (estado → fallback global)
     */
    public function getEffectivePrice(?int $companyLocationId = null): float
    {
        if ($companyLocationId) {
            $price = $this->prices()
                ->where('company_location_id', $companyLocationId)
                ->where('is_active', true)
                ->value('price');

            if (!is_null($price)) {
                return (float) $price;
            }
        }

        return (float) ($this->global_price ?? 0);
    }

    /**
     * 🔑 Precio payout según trailer
     */
    public function getCrewPrice(bool $hasTrailer): float
    {
        return (float) (
            $hasTrailer
                ? $this->crew_price_with_trailer
                : $this->crew_price_without_trailer
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
