<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->unsignedInteger('make_id')->index();
            $table->timestamps();
            $table->softDeletes();

            // models that  belong to a make
            $table->foreign('make_id')->references('id')->on('vehicle_makes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_models');
    }
}
