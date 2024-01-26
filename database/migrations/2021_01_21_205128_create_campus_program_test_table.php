<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusProgramTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus_program_test', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('campus_program_id');
            $table->integer('test_id');
            $table->integer('score');
            $table->timestamps();
            $table->boolean('show_in_front')->default(0);
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
        Schema::dropIfExists('campus_program_test');
    }
}
