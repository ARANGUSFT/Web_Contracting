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
            $table->string('name');
            $table->string('last_name')->nullable();

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            $table->string('phone')->nullable();
            $table->string('language')->default('English');
            $table->string('profile_photo')->nullable();

            $table->string('company_name')->nullable();
            $table->json('residential_roof_types')->nullable();
            $table->json('commercial_roof_types')->nullable();
            $table->json('states_you_can_work')->nullable();
            $table->boolean('all_states')->default(false);
            $table->string('years_experience')->nullable();
            $table->json('company_documents')->nullable();

            
            $table->string('password');
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
