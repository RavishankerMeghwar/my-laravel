<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColInTemplateComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('template_component', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')->nullable()->after('id');
            $table->foreign('group_id')->references('id')->on('groups')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_component', function (Blueprint $table) {
            $table->dropForeign('template_component_group_id_foreign');
            $table->dropColumn('group_id');
        });
    }
}
