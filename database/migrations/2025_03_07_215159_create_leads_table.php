<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('estado')->nullable();
            $table->string('company_name')->nullable();
            $table->string('cross_reference')->nullable();
            $table->string('job_category')->nullable();
            $table->string('work_type')->nullable();
            $table->string('job_trades')->nullable();
            $table->string('lead_source')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_ext')->nullable();
            $table->string('phone_type')->nullable();
            $table->string('email')->nullable();
            $table->string('street')->nullable();
            $table->string('suite')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('streetmailing')->nullable();
            $table->string('suitemailing')->nullable();
            $table->string('citymailing')->nullable();
            $table->string('zipmailing')->nullable();
            $table->string('billing_address')->nullable();
            $table->string('streetbilling')->nullable();
            $table->string('suitebilling')->nullable();
            $table->string('citybilling')->nullable();
            $table->string('statebilling')->nullable();
            $table->string('zipbilling')->nullable();
            $table->string('insurance_company')->nullable();
            $table->string('adjuster_phone_type')->nullable();
            $table->string('damage_location')->nullable();
            $table->date('date_loss')->nullable();
            $table->string('claim_number')->nullable();
            $table->string('adjuster_phone')->nullable();
            $table->string('adjuster_ext')->nullable();
            $table->string('adjuster_fax')->nullable();
            $table->string('adjuster_email')->nullable();
            $table->text('notas')->nullable();
            $table->unsignedBigInteger('id_padre')->nullable();
            $table->json('finanzas')->nullable();
            $table->json('files')->nullable();
            $table->json('anexos')->nullable();
            $table->json('contratos')->nullable();
            $table->json('location_photo')->nullable();
            $table->timestamps();

            // Nueva columna para el vendedor asignado
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id')->references('id')->on('team')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
