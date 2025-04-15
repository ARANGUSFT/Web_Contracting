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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_id')->nullable()->constrained()->onDelete('set null');

            $table->integer('sq');
            $table->decimal('material_cost_per_sq', 10, 2);
            $table->decimal('labor_cost_per_sq', 10, 2);
            $table->decimal('other_costs', 10, 2);
            $table->decimal('material_total', 10, 2);
            $table->decimal('labor_total', 10, 2);
            $table->decimal('profit', 10, 2);
            $table->decimal('quote_total', 10, 2);
            $table->decimal('percentage', 5, 2)->default(30);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
