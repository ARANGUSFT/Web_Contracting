<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_team', function (Blueprint $table) {
            $table->id();

            $table->foreignId('emergency_id')
                ->constrained('emergencies')
                ->onDelete('cascade');

            $table->foreignId('team_id')
                ->constrained('team') // 👈 asegúrate que tu tabla realmente se llama "team"
                ->onDelete('cascade');

            $table->timestamps();

            // 🔥 Evita duplicados
            $table->unique(['emergency_id', 'team_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_team');
    }
};
