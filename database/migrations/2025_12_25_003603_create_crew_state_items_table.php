<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crew_state_items', function (Blueprint $table) {
            $table->id();

            // Relación con crew
            $table->foreignId('crew_id')
                  ->constrained('crews')
                  ->cascadeOnDelete();

            // Estado (TX, NJ, etc)
            $table->string('state', 50);

            // Item info
            $table->string('name');
            $table->text('description')->nullable();

            // Precio
            $table->decimal('price', 10, 2);

            $table->timestamps();

            // Evita duplicados
            $table->unique(
                ['crew_id', 'state', 'name'],
                'crew_state_item_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crew_state_items');
    }
};
