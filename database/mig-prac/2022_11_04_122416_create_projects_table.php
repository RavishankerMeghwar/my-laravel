<?php

use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpKernel\Profiler\Profile;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('project_template_id')->nullable();
            $table->enum('customer_type', [
                Project::CUSTOMER_TYPE_COMPANY,
                Project::CUSTOMER_TYPE_PRIVATE
            ])->default(Project::CUSTOMER_TYPE_COMPANY);
            $table->enum('gender_type', [
                Project::GENDER_TYPE_MR,
                Project::GENDER_TYPE_WOMEN,
                Project::GENDER_TYPE_NOT_SPECIFIED
            ])->default(Project::GENDER_TYPE_NOT_SPECIFIED);
            $table->enum('phone_type', [
                Project::PHONE_TYPE_PRIVATE,
                Project::PHONE_TYPE_MOBILE,
                Project::PHONE_TYPE_STORE
            ])->default(Project::PHONE_TYPE_PRIVATE);
            $table->enum('project_status', [
                Project::PROJECT_CREATED,
                Project::OFFER_EXPECTED,
                Project::OFFER_SEND,
                Project::SOLD,
                Project::CUSTOMER_SERVICE,
                Project::OFFER_MADE,
                Project::OFFER_CHANGED,
                Project::OFFER_REJECTED,
                Project::PROJECT_COMPLETED
            ])->default(Project::PROJECT_CREATED);
            $table->string('title')->nullable();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('language')->nullable();
            $table->string('address')->nullable();
            $table->string('reference')->nullable();
            $table->string('commissioning_date')->nullable();
            $table->string('street')->nullable();
            $table->integer('no')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('location')->nullable();
            $table->string('land')->nullable();
            $table->string('private_tel')->nullable();
            $table->string('mobile_phone')->nullable();    
            $table->double('tax_saving')->nullable();
            $table->double('vat')->nullable();
            $table->bigInteger('flags')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // foreign_key_constraints
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_template_id')->references('id')->on('project_templates')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
