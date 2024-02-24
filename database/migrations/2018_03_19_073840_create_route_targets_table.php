<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['Yearly', 'Monthly', 'Weekly', 'Daily']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('target', 100)->nullable();
            $table->string('achieved', 100)->nullable();
            $table->unsignedInteger('route_id')->index();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
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
        Schema::dropIfExists('route_targets');
    }
}
