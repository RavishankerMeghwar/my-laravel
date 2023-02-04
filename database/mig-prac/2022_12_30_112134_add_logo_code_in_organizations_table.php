<?php

use App\Models\Organization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


class AddLogoCodeInOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $organization = Organization::find(1);
        $dir = public_path('assets/organizations/logo.png');
        $path = Storage::putFile('organizations/' . $organization->id, new File($dir));
        $organization->image = $path;
        $organization->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
