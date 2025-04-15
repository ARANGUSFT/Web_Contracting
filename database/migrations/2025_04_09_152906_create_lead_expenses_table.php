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
        Schema::create('lead_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
        
            $table->date('expense_date');
        
            // Material cost como valor decimal
            $table->decimal('material', 12, 2)->nullable();
        
            $table->decimal('labor_cost', 12, 2)->nullable();
            $table->decimal('commission_percentage', 5, 2)->nullable();
            $table->string('permit')->nullable();
            $table->decimal('supplement', 12, 2)->nullable();
            $table->decimal('other_expenses', 12, 2)->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_expenses');
    }
};
