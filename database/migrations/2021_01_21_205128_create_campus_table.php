<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->integer('university_id');
            $table->string('logo', 255)->nullable();
            $table->string('cover', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->bigInteger('address_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->text('about_us')->nullable();
            $table->text('feature')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campus');
    }
}
