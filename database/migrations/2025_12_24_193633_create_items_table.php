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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // 🔑 RELACIÓN DIRECTA CON EL ESTADO
            $table->foreignId('company_location_id')
                ->constrained()
                ->cascadeOnDelete();

            // Item info
            $table->string('name');
            $table->text('description')->nullable();

            // 💰 PRECIO DEL ITEM EN ESTE ESTADO
            $table->decimal('price', 10, 2)->default(0);

            // Orden y estado
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
