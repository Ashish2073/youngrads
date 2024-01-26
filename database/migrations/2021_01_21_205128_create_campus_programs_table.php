<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus_programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campus_id');
            $table->bigInteger('program_id');
            $table->string('entry_requirment', 255)->nullable();
            $table->integer('campus_program_duration')->nullable();
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
        Schema::dropIfExists('campus_programs');
    }
}
