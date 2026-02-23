<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            // Relación con factura
            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            // Relación con item base (opcional pero recomendado)
            $table->foreignId('item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('description');
            $table->text('note')->nullable(); // 🔥 aquí sin after()
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
