<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('subcontractor_paid')->default(false)->after('status');
            $table->timestamp('subcontractor_paid_at')->nullable()->after('subcontractor_paid');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['subcontractor_paid', 'subcontractor_paid_at']);
        });
    }
};