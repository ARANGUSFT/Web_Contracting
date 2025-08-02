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
        Schema::create('event_notes', function (Blueprint $table) {
            $table->id();

            // Relación polimórfica
            $table->string('noteable_type');
            $table->unsignedBigInteger('noteable_id');

            // Emisor: puede ser un usuario del sistema o un subcontratista
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('subcontractor_id')->nullable()->constrained()->onDelete('cascade');

            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_notes');
    }
};
