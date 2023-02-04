<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturerModalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturer_modal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('component_id');
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('modal_id');
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            //relationship
            $table->foreign('component_id')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onUpdate('cascade')->onDelete('cascade');
            
            $table->foreign('modal_id')->references('id')->on('modals')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturer_modal');
    }
}
