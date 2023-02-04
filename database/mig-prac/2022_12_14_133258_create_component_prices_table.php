<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('component_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('component_id')->nullable();
            $table->double('price_level')->nullable();
            $table->double('cost_price')->nullable();
            $table->double('calculation_surcharge')->nullable();
            $table->double('installation_cost')->nullable();
            $table->double('selling_price')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('component_id')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('component_prices');
    }
}
