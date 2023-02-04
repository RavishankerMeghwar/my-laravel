<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvInverterComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv_inverter_components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pv_inverter_id')->nullable();
            $table->unsignedBigInteger('inverter')->nullable();
            $table->timestamps();

            //relationship
            $table->foreign('pv_inverter_id')->references('id')->on('pv_inverters')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inverter')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pv_inverter_components');
    }
}
