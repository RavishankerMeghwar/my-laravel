<?php

use App\Models\Tariff;
use App\Models\TariffData;
use App\Models\Tariffs;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('energy_supp_id')->nullable();
            $table->string('title')->nullable();
            $table->double('low_tariff')->nullable();
            $table->double('high_tariff')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            $table->foreign('energy_supp_id')->references('id')->on('energy_suppliers')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}
