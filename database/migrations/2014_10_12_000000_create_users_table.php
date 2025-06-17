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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Personal Info
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('language')->default('English');
            $table->string('profile_photo')->nullable();

            // Company Info
            $table->string('company_name')->nullable();
            $table->string('years_experience')->nullable();
            $table->json('residential_roof_types')->nullable();
            $table->json('commercial_roof_types')->nullable();
            $table->json('states_you_can_work')->nullable();
            $table->boolean('all_states')->default(false);
            $table->json('company_documents')->nullable();

            // Login Info
            $table->string('password');
            $table->boolean('is_admin')->default(false); // 🔑 Para acceso al panel
            $table->boolean('is_active')->default(true); // Estado activo/inactivo del usuario
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
