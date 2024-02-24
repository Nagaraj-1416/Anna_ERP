<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rep_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['Yearly', 'Monthly', 'Weekly', 'Daily']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('target', 100)->nullable();
            $table->string('achieved', 100)->nullable();
            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps')->onDelete('cascade');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
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
        Schema::dropIfExists('rep_targets');
    }
}
