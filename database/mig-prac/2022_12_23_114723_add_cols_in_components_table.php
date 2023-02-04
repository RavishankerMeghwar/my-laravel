<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsInComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('components', function (Blueprint $table) {
            $table->string('unit')->nullable()->after('item_number');
            $table->enum('language', [
                User::LANGUAGE_DUTCH,
                user::LANGUAGE_ENGLISH,
                user::LANGUAGE_OTHER
            ])->default(User::LANGUAGE_DUTCH)->after('unit');
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
            $table->dropColumn('unit')->nullable();
            $table->dropColumn('language');
        });
    }
}
