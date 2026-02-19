<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('item_categories')
                ->nullOnDelete();

            $table->string('name');

            // 💰 PRECIO GLOBAL (OPCIONAL)
            $table->decimal('global_price', 10, 2)->nullable();

            // 💰 PRECIO CREW CON TRAILER
            $table->decimal('crew_price_with_trailer', 10, 2)->nullable();

            // 💰 PRECIO CREW SIN TRAILER
            $table->decimal('crew_price_without_trailer', 10, 2)->nullable();

            // Orden visual
            $table->integer('sort_order')->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
