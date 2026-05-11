<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weekly_accounting_costs', function (Blueprint $table) {
            $table->id();
            $table->date('week_start')->comment('Wednesday - start of accounting week');
            $table->date('week_end')->comment('Tuesday - end of accounting week');
            $table->unique(['week_start', 'week_end']);

            // Costos operativos ingresados manualmente
            $table->decimal('landfill', 12, 2)->default(0);
            $table->decimal('fuel', 12, 2)->default(0);
            $table->decimal('other', 12, 2)->default(0);
            $table->decimal('driver', 12, 2)->default(0);
            $table->decimal('superintendent', 12, 2)->default(0);
            $table->decimal('ceo', 12, 2)->default(0);

            $table->text('notes')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index('week_start');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weekly_accounting_costs');
    }
};