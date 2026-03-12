<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotesToLeadExpensesTable extends Migration
{
    public function up()
    {
        Schema::table('lead_expenses', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('amount');
        });
    }

    public function down()
    {
        Schema::table('lead_expenses', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}