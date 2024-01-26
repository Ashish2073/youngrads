<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_academics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id');
            $table->string('course_name');
            $table->string('year_of_passing')->nullable();
            $table->string('board_name');
            $table->string('marks', 50);
            $table->string('marks_unit');
            $table->timestamps();
            $table->tinyInteger('study_levels_id')->nullable();
            $table->tinyInteger('country')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('language')->nullable();
            $table->string('qualification')->nullable();
            $table->string('sub_other', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_academics');
    }
}
