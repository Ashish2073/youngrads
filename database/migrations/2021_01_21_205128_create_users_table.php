<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('email');
            $table->string('username')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('provider_id')->nullable();
            $table->string('profile_img')->nullable();
            $table->string('language')->nullable();
            $table->string('personal_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('maritial_status')->nullable();
            $table->date('dob')->nullable();
            $table->bigInteger('country')->nullable();
            $table->string('passport', 20)->nullable();
            $table->string('primary_language', 100)->nullable();
            $table->string('postal', 10)->nullable();
            $table->integer('address_id')->nullable();
            $table->string('new_email')->nullable();
            $table->softDeletes();
        });
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
