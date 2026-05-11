<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json
            $table->string('group')->default('general');
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed settings iniciales
        \DB::table('app_settings')->insert([
            [
                'key'         => 'equity_percentage',
                'value'       => '4.00',
                'type'        => 'number',
                'group'       => 'accounting',
                'label'       => 'A&F Equity Percentage',
                'description' => 'Percentage of Weekly Gross allocated to the company (A&F Payment).',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'key'         => 'week_start_day',
                'value'       => '3',
                'type'        => 'number',
                'group'       => 'accounting',
                'label'       => 'Week start day',
                'description' => 'Day when accounting week starts. 0=Sun, 1=Mon, 2=Tue, 3=Wed, 4=Thu, 5=Fri, 6=Sat.',
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};