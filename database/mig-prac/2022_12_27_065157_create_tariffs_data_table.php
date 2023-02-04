<?php

use App\Models\Tariff;
use App\Models\TariffData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tariff_id')->nullable();
            $table->enum('day', [
                TariffData::MON_TO_FRI,
                TariffData::SATURDAY,
                TariffData::SUNDAY
            ])->nullable();
            $table->string('hour')->nullable();
            $table->string('value')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();

            
            $table->foreign('tariff_id')->references('id')->on('tariffs')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs_data');
    }
}
