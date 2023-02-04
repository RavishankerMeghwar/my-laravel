<?php

use App\Models\PvModule;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePvModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pv_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pv_module')->nullable();
            $table->unsignedBigInteger('sub_structure')->nullable();
            $table->enum('arrangement', [
                PvModule::HORIZONTAL,
                PvModule::VERTICAL
            ])->nullable();
            $table->string('dach_name')->nullable();
            $table->double('orientation')->nullable();
            $table->double('module_tilt')->nullable();
            $table->double('specific_yield')->nullable();
            $table->string('roof_type')->nullable();
            $table->string('first_hole')->nullable();
            $table->string('rafter')->nullable();
            $table->enum('roof_convering', [
                PvModule::INTERLOCKING_TILES,
                PvModule::BIBERSCHWANZ,
                PvModule::TRAPEZOISlDAL_SHEET_MENTAL,
                PvModule::WELLETEMIT,
                PvModule::ETEMITSCHINDEL,
                PvModule::STEHFALZ
            ])->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            // foreign keys    
            $table->foreign('pv_module')->references('id')->on('components')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('pv_modules');
    }
}
