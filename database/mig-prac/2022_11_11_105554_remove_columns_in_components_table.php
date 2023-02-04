<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsInComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->dropForeign('components_manufacturer_id_foreign');
            $table->dropForeign('components_modal_id_foreign');
            $table->dropColumn('manufacturer_id');
            $table->dropColumn('modal_id');
            $table->dropColumn('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->unsignedBigInteger('manufacturer_id');
            $table->unsignedBigInteger('modal_id');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('modal_id')->references('id')->on('modals')->onUpdate('cascade')->onDelete('cascade');
            $table->string('image')->nullable();
        });
    }
}
