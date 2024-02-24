<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('mime')->nullable();
            $table->string('extension')->nullable();
            $table->string('size');
            $table->string('documentable_type')->nullable();
            $table->unsignedInteger('documentable_id')->nullable();
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();

            // The documents that belong to that user
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
