<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('application_number', 255)->nullable();
            $table->integer('user_id');
            $table->integer('intake_id');
            $table->string('year', 10)->nullable();
            $table->integer('campus_program_id');
            $table->string('status')->default('pending');
            $table->string('admin_status')->default('active');
            $table->boolean('is_favorite')->default(0);
            $table->tinyInteger('priority')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('users_applications');
    }
}
