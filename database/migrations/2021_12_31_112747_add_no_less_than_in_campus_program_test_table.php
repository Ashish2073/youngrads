<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoLessThanInCampusProgramTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campus_program_test', function (Blueprint $table) {
            $table->double('nlt_score')->nullable()->after('score')->comment('no less than score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campus_program_test', function (Blueprint $table) {
            $table->dropColumn(['nlt_score']);
        });
    }
}
