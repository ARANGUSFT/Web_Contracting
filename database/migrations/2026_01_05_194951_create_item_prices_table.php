<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();

            // Empresa + Estado
            $table->foreignId('company_location_id')
                ->constrained('company_locations')
                ->cascadeOnDelete();

            // Item global
            $table->foreignId('item_id')
                ->constrained('items')
                ->cascadeOnDelete();

            // Precio del item para esa empresa y estado
            $table->decimal('price', 10, 2)->default(0);

            // Activar / desactivar item en ese estado
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // 🔐 Evita duplicados: 1 precio por item por empresa+estado
            $table->unique(['company_location_id', 'item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
