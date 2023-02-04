<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->enum('role', [
                User::ROLE_MANAGER,
                User::ROLE_EMPLOYEE,
                User::ROLE_SUPER_ADMIN,
                'general'
            ])->default('general')->after('other');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}
