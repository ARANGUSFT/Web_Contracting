<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('lead_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->date('expense_date');
            $table->enum('type', ['material', 'labor', 'commission', 'permit', 'supplement', 'other']);
            $table->decimal('amount', 10, 2);
            $table->timestamps();

            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_expenses');
    }
}
