<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadFinanzasTable extends Migration
{
    public function up()
    {
        Schema::create('lead_finanzas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->date('date');
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable();
            $table->string('check_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        

        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('contract_value', 12, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_finanzas');

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('contract_value');
        });
    }
}
