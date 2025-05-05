<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_requests', function (Blueprint $table) {
            $table->id();

            // General Info
            $table->date('install_date_requested');
            $table->string('company_name');
            $table->string('company_rep');
            $table->string('company_rep_phone');
            $table->string('company_rep_email')->nullable();

            // Customer Info
            $table->string('customer_first_name');
            $table->string('customer_last_name')->nullable();
            $table->string('customer_phone_number');

            // Job Address
            $table->string('job_number_name');
            $table->string('job_address_street_address');
            $table->string('job_address_street_address_line_2')->nullable();
            $table->string('job_address_city');
            $table->string('job_address_state');
            $table->string('job_address_zip_code');

            // Material Ordered
            $table->enum('material_roof_loaded', ['Yes', 'No']);
            $table->integer('starter_bundles_ordered')->nullable();
            $table->integer('hip_and_ridge_ordered')->nullable();
            $table->integer('field_shingle_bundles_ordered')->nullable();
            $table->integer('modified_bitumen_cap_rolls_ordered')->nullable();
            $table->date('delivery_date')->nullable();

            // Inspections and Replacements
            $table->enum('mid_roof_inspection', ['Yes', 'No'])->nullable();
            $table->enum('siding_being_replaced', ['Yes', 'No'])->nullable();
            $table->integer('asphalt_shingle_layers_to_remove')->nullable();
            $table->enum('re_deck', ['Yes', 'No'])->nullable();
            $table->enum('skylights_replace', ['Yes', 'No'])->nullable();
            $table->enum('gutter_remove', ['Yes', 'No'])->nullable();
            $table->enum('gutter_detached_and_reset', ['Yes', 'No'])->nullable();
            $table->enum('satellite_remove', ['Yes', 'No'])->nullable();
            $table->enum('satellite_goes_in_the_trash', ['Yes', 'No'])->nullable();
            $table->enum('open_soffit_ceiling', ['Yes', 'No'])->nullable();
            $table->enum('detached_garage_roof', ['Yes', 'No'])->nullable();
            $table->enum('detached_shed_roof', ['Yes', 'No'])->nullable();

            // Additional
            $table->text('special_instructions')->nullable();
            $table->boolean('material_verification')->default(false);
            $table->boolean('stop_work_request')->default(false);
            $table->boolean('documentationattachment')->default(false);

            // Files
            $table->string('aerial_measurement')->nullable();
            $table->string('material_order')->nullable();
            $table->string('file_upload')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_requests');
    }
};
