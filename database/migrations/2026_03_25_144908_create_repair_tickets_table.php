<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('crew_id')->nullable();
            $table->string('reference_type');
            $table->unsignedBigInteger('reference_id');
            $table->unsignedSmallInteger('sequence_number')->default(1);
            $table->date('repair_date');
            $table->text('description');
            $table->string('status')->default('pending');

            // ── Pago ──────────────────────────────────────────
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->date('payment_date')->nullable();
            $table->string('payment_receipt_path')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('crew_id')->references('id')->on('crews')->onDelete('set null');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_tickets');
    }
};