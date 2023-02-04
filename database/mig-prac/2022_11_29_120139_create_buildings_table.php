<?php

use App\Models\Building;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->enum('building_type', [
                Building::DETACHED_HOUSE,
                Building::APARTMENT_BUILDING,
                Building::OFFICE_BUILDING,
                Building::INDUSTRIAL_BUILDING,
                Building::TOWN_HOUSE
            ])->nullable();
            $table->string('construction_year')->nullable();
            $table->string('last_renovation')->nullable();
            $table->integer('no_of_people')->nullable();
            $table->string('room_temperature')->nullable();
            $table->integer('no_of_residential_unit')->nullable();
            $table->integer('no_of_floor')->nullable();
            $table->string('evergy_reference_area')->nullable();
            $table->string('egid')->nullable();
            $table->text('description')->nullable();
            $table->enum('pv_system', [
                Building::OWN_CONSUMPTION,
                Building::FULL_FEED,
                Building::OWN_CONSUMPTION_ZEV
            ])->nullable();
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
        Schema::dropIfExists('buildings');
    }
}
