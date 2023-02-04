<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('component_type_id');
            $table->unsignedBigInteger('manufacturer_id')->nullable(); //removed
            $table->unsignedBigInteger('modal_id')->nullable(); //removed
            $table->string('name')->nullable();
            $table->string('item_number')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable(); //removed
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade'); 
            $table->foreign('component_type_id')->references('id')->on('component_types')->onUpdate('cascade')->onDelete('cascade'); 
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onUpdate('cascade')->onDelete('cascade'); //removed
            $table->foreign('modal_id')->references('id')->on('modals')->onUpdate('cascade')->onDelete('cascade'); //removed
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('components');
    }
}
