<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabla pivote para Job Requests
        Schema::create('job_request_team', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('job_request_id');
            $table->foreign('job_request_id')
                ->references('id')->on('job_requests')
                ->onDelete('cascade');

            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')
                ->references('id')->on('team') // nombre correcto de la tabla
                ->onDelete('cascade');

            $table->timestamps();
        });

        // Tabla pivote para Emergencies
        Schema::create('emergency_team', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('emergency_id');
            $table->foreign('emergency_id')
                ->references('id')->on('emergencies')
                ->onDelete('cascade');

            $table->unsignedBigInteger('team_id');
            $table->foreign('team_id')
                ->references('id')->on('team')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_team');
        Schema::dropIfExists('job_request_team');
    }
};
