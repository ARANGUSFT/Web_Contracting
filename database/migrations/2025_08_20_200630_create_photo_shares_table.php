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
        Schema::create('photo_shares', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20);                   // 'job_request' | 'emergency'
            $table->unsignedBigInteger('model_id');       // id del JobRequest/Emergencies
            $table->string('token', 64)->unique();        // token público
            $table->timestamp('expires_at')->nullable();  // opcional
            $table->boolean('is_active')->default(true);  // revocar sin borrar
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_shares');
    }
};
