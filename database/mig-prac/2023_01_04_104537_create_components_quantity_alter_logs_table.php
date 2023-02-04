<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentsQuantityAlterLogsTable extends Migration
{
    public function up()
    {
        Schema::create('components_quantity_alter_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('component_id')->nullable();
            $table->foreign('component_id')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('old_quantity')->default(0);
            $table->integer('new_quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('components_quantity_alter_logs');
    }
}
