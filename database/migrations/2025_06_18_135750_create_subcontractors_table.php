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
        Schema::create('subcontractors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            
            $table->json('residential_roof_types')->nullable();
            $table->json('commercial_roof_types')->nullable();
            $table->json('states_you_can_work')->nullable();
            $table->boolean('all_states')->default(false);

            $table->string('state');
            $table->string('password'); // Campo para la contraseña
            $table->boolean('is_active')->default(true); // Campo para activar/desactivar cuenta
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subcontractors');
    }
};
