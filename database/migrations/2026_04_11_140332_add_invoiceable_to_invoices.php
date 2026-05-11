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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('invoiceable_type')->nullable()->after('id');
            $table->unsignedBigInteger('invoiceable_id')->nullable()->after('invoiceable_type');
            $table->index(['invoiceable_type', 'invoiceable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['invoiceable_type', 'invoiceable_id']);
            $table->dropColumn(['invoiceable_type', 'invoiceable_id']);
        });
    }
};
