<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('code', 10)->unique('currency_code_unique');
            $table->string('symbol', 10);
            $table->decimal('rate', 10);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->enum('default', ['1', '0']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
