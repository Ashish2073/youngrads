<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusProgramFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus_program_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campus_program_id');
            $table->bigInteger('fee_type_id');
            $table->string('fee_price', 255);
            $table->bigInteger('fee_currency');
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
        Schema::dropIfExists('campus_program_fees');
    }
}
