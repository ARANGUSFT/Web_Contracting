<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // add_payment_fields_to_emergencies_table
    public function up(): void
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable()->after('status');
            $table->string('payment_receipt_path')->nullable()->after('amount');
            $table->date('payment_date')->nullable()->after('payment_receipt_path');
            $table->enum('payment_status', ['unpaid', 'paid'])->default('unpaid')->after('payment_date');
        });
    }

    public function down(): void
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->dropColumn(['amount', 'payment_receipt_path', 'payment_date', 'payment_status']);
        });
    }
};
