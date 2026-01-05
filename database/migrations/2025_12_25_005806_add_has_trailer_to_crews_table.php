<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crews', function (Blueprint $table) {
            $table->boolean('has_trailer')
                  ->default(false)
                  ->after('states');
        });
    }

    public function down(): void
    {
        Schema::table('crews', function (Blueprint $table) {
            $table->dropColumn('has_trailer');
        });
    }
};
