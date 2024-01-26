<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_timelines', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('application_id');
            $table->string('status', 255);
            $table->bigInteger('user_id');
            $table->string('user_type', 255);
            $table->text('description')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_timelines');
    }
}
