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
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Asociación con usuarios
            $table->foreignId('crew_id')->nullable()->constrained()->onDelete('set null');

            $table->date('date_submitted');
            $table->string('type_of_supplement');
            $table->string('company_name');
            $table->string('company_contact_email');
            $table->string('job_number_name');
            $table->string('job_address');
            $table->string('job_address_line2')->nullable();
            $table->string('job_city');
            $table->string('job_state');
            $table->string('job_zip_code');
            $table->boolean('terms_conditions');
            $table->boolean('requirements');
            $table->json('aerial_measurement_path');
            $table->json('contract_upload_path');
            $table->json('file_picture_upload_path')->nullable();

            // Estado del trabajo
            $table->enum('status', ['pending', 'en_process', 'completed'])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};
