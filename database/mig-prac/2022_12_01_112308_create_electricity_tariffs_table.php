<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectricityTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('electricity_tariffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('energy_supp_id')->nullable();
            $table->unsignedBigInteger('tariff_id')->nullable();
            $table->double('consumption_tariff')->nullable();
            $table->double('feed_in_tariff')->nullable();
            $table->double('performance_tariff')->nullable();
            $table->double('load_meter')->nullable();
            $table->double('change_to')->nullable();
            $table->double('after_zero_year')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('energy_supp_id')->references('id')->on('energy_suppliers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tariff_id')->references('id')->on('tariffs')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('electricity_tariffs');
    }
}
