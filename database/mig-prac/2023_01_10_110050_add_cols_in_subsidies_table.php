<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsInSubsidiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subsidies', function (Blueprint $table) {
            $table->double('grb')->nullable()->after('hbbt');
            $table->double('lsb1')->nullable()->after('grb');
            $table->double('lsb2')->nullable()->after('lsb1');
            $table->double('lsb3')->nullable()->after('lsb2');
            $table->enum('nw', ['true','false'])->nullable()->after('lsb3');
            $table->enum('hb', ['true','false'])->nullable()->after('nw');
            $table->enum('ev', ['true','false'])->nullable()->after('hb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subsidies', function (Blueprint $table) {
            $table->dropColumn('grb');
            $table->dropColumn('lsb1');
            $table->dropColumn('lsb2');
            $table->dropColumn('lsb3');
            $table->dropColumn('nw');
            $table->dropColumn('hb');
            $table->dropColumn('ev');
        });
    }
}
