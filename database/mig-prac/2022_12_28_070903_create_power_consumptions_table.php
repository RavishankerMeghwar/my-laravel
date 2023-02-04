<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePowerConsumptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('power_consumptions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('building_type')->nullable();
            $table->string('heating_system')->nullable();
            $table->string('water_system')->nullable();
            $table->double('annual_consumption')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('power_consumptions');
    }
}
