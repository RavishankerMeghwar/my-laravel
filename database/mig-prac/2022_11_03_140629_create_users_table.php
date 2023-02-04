<?php

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;


class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('user_name');
            $table->string('email');
            $table->string('telephone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->string('reset_token')->nullable();
            $table->string('verification_code')->nullable();
            $table->enum('language', [
                User::LANGUAGE_DUTCH,
                user::LANGUAGE_ENGLISH
            ])->default(User::LANGUAGE_DUTCH);
            $table->enum('role', [
                User::ROLE_MANAGER,
                user::ROLE_EMPLOYEE,
                user::ROLE_SUPER_ADMIN
            ])->default(User::ROLE_EMPLOYEE);
            $table->bigInteger('flags')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // foreign_key_constraints
            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
        });
        // insert organization
        
        $organization        = new Organization();
        $organization->title = 'SuperOrganization';
        $organization->token = Str::uuid();
        $organization->addFlag(Organization::FLAG_ACTIVE);
        $organization->save();
        
        // insert super admin
        $user                  = new User();
        $user->organization_id = $organization->id;
        $user->first_name      = 'SuperAdmin';
        $user->last_name       = 'Admin';
        $user->user_name       = 'SuperAdmin';
        $user->email           = 'superadmin@gmail.com';
        $user->password        = 123123;
        $user->role            = User::ROLE_SUPER_ADMIN;
        $user->addFlag(User::FLAG_ACTIVE);
        $user->addFlag(User::FLAG_EMAIL_VERIFIED);
        $user->save();

        $user                  = new User();
        $user->organization_id = $organization->id;
        $user->first_name      = 'Manager';
        $user->last_name       = 'manager';
        $user->email           = 'manager@gmail.com';
        $user->user_name       = 'manager@gmail.com';
        $user->password        = 123123;
        $user->role            = User::ROLE_MANAGER;
        $user->addFlag(User::FLAG_ACTIVE);
        $user->addFlag(User::FLAG_EMAIL_VERIFIED);
        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
