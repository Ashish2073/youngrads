<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDocumentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_document_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_document_id');
            $table->integer('file_id');
            $table->timestamps();
            $table->tinyInteger('table_id')->nullable();
            $table->string('table_name', 50)->nullable();
            $table->string('type', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_document_files');
    }
}
