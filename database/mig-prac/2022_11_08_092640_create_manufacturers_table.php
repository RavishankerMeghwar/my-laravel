<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('component_type_id');
            $table->string('manufacturer');
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // Foreign constraints
            $table->foreign('component_type_id')->references('id')->on('component_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturers');
    }
}
