<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lead_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('image_path');
            $table->string('file_name')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->string('file_hash', 64)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['lead_id', 'file_hash']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_images');
    }
};
