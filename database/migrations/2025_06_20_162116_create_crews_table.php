<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('crews', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // Crew name
            $table->string('company');      // Company name
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->json('states')->nullable();
            $table->boolean('is_active')->default(true); // Nuevo campo para activar/desactivar la crew
            $table->timestamps();
        });
        
    }

    public function down(): void {
        Schema::dropIfExists('crews');
    }
};
