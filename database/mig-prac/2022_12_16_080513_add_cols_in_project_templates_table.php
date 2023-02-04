<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsInProjectTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->unsignedBigInteger('pv_module')->nullable()->after('id');
            $table->unsignedBigInteger('inverter')->nullable()->after('pv_module');
            $table->unsignedBigInteger('sub_structure')->nullable()->after('inverter');
            //foreign keys
            $table->foreign('pv_module')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inverter')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('sub_structure')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_templates', function (Blueprint $table) {
            $table->dropForeign('project_templates_pv_module_foreign');
            $table->dropForeign('project_templates_inverter_foreign');
            $table->dropForeign('project_templates_sub_structure_foreign');
            $table->dropColumn('pv_module');
            $table->dropColumn('inverter');
            $table->dropColumn('sub_structure');
        });
    }
}
