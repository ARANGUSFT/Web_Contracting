<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeeklyAccountingCost extends Model
{
    protected $table = 'weekly_accounting_costs';

    protected $fillable = [
        'week_start',
        'week_end',
        'landfill',
        'fuel',
        'other',
        'driver',
        'superintendent',
        'ceo',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'week_start'     => 'date',
        'week_end'       => 'date',
        'landfill'       => 'decimal:2',
        'fuel'           => 'decimal:2',
        'other'          => 'decimal:2',
        'driver'         => 'decimal:2',
        'superintendent' => 'decimal:2',
        'ceo'            => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }

    /**
     * Total of all operating costs.
     */
    public function getOperatingTotalAttribute(): float
    {
        return (float) ($this->landfill + $this->fuel + $this->other
                      + $this->driver + $this->superintendent + $this->ceo);
    }
}