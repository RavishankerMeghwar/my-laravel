<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceParameterColsInComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->string('price_dependency')->nullable()->after('item_number');
            $table->string('price_type')->nullable()->after('price_dependency');
            $table->string('price_definition')->nullable()->after('price_type');
            $table->string('price_repetition')->nullable()->after('price_definition');
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
            $table->dropColumn(['price_dependency','price_type','price_definition','price_repetition']);
        });
    }
}
