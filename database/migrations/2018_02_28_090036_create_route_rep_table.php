<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouteRepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('route_rep', function (Blueprint $table) {
            $table->unsignedInteger('route_id')->index();
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps')->onDelete('cascade');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('route_rep');
    }
}
