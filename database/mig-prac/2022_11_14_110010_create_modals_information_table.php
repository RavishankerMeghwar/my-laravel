<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modals_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modal_id');
            $table->string('group');
            $table->string('language');
            $table->string('label');
            $table->string('value');
            $table->text('description')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // Foreign key constraints
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
        Schema::dropIfExists('modals_information');
    }
}
