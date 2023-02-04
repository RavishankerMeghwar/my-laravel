<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubsidiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subsidies', function (Blueprint $table) {
            $table->id();
            $table->string('pronovo_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->double('lsg')->nullable()->default(0);
            $table->integer('ibm')->nullable();
            $table->boolean('kat')->nullable();
            $table->double('vgb')->nullable();
            $table->double('nwbt')->nullable()->default(0);
            $table->double('hbbt')->nullable()->default(0);
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
        Schema::dropIfExists('subsidies');
    }
}
