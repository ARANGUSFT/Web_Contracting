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
        // Crear tabla lead_approvals
        Schema::create('lead_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            // Relación con usuario
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('company_representative');
            $table->string('company_phone');
            $table->string('lead_name');
            $table->string('lead_address');
            $table->string('lead_phone');
            $table->date('installation_date');
            $table->text('extra_info')->nullable();
            $table->timestamps();
        });

        // Agregar columna a tabla leads
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('approved_data_submitted')->default(false)->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar columna de leads
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('approved_data_submitted');
        });

        // Eliminar tabla lead_approvals
        Schema::dropIfExists('lead_approvals');
    }
};
