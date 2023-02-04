<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColPvModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pv_modules', function (Blueprint $table) {
            $table->unsignedBigInteger('project_id')->nullable()->after('id');
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pv_modules', function (Blueprint $table) {
            $table->dropForeign('pv_modules_project_id_foreign');
            $table->dropColumn('project_id');
        });
    }
}
