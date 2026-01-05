<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_create_invoice_attachments_table.php
    public function up()
    {
        Schema::create('invoice_attachments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('original_name');
            $table->string('file_path');
            $table->string('mime_type')->nullable();
            $table->integer('size')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoice_attachments');
    }


  
};
