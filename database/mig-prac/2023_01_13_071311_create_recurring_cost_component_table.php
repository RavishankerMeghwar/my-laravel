<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringCostComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recurring_cost_component', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('recurring_cost_id');
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // foreign keys    
            $table->foreign('template_id')->references('id')->on('project_templates')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('recurring_cost_id')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurring_cost_component');
    }
}
