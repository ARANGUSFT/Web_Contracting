<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subcontractors;
use Carbon\Carbon;

class DeactivateExpiredSubcontractors extends Command
{
    protected $signature = 'subcontractors:check-expiration';
    protected $description = 'Deactivate subcontractors if all their insurances are expired';

    public function handle()
    {
        $now = Carbon::today();

        Subcontractors::with('insurances')->chunk(100, function ($subcontractors) use ($now) {
            foreach ($subcontractors as $sub) {
                // Verifica si tiene al menos un seguro válido
                $hasValidInsurance = $sub->insurances->contains(function ($insurance) use ($now) {
                    return Carbon::parse($insurance->expires_at)->gte($now);
                });

                // Actualiza el estado solo si es diferente
                if ($sub->is_active !== $hasValidInsurance) {
                    $sub->update(['is_active' => $hasValidInsurance]);
                }
            }
        });

        $this->info('Subcontractor statuses updated based on insurance expiration.');
    }
}
