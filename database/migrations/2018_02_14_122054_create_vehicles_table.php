<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('vehicle_no', 50);
            $table->string('engine_no', 50);
            $table->string('chassis_no', 50);
            $table->date('reg_date');
            $table->string('year', 10);
            $table->string('color');
            $table->enum('fuel_type', ['Petrol', 'Diesel', 'Electric', 'Other']);
            $table->string('image')->nullable();
            $table->string('type_of_body')->nullable();
            $table->string('seating_capacity')->nullable();
            $table->string('weight')->nullable();
            $table->string('gross')->nullable();
            $table->string('tyre_size_front')->nullable();
            $table->string('tyre_size_rear')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('wheel_front')->nullable();
            $table->string('wheel_rear')->nullable();
            $table->unsignedInteger('type_id')->index();
            $table->unsignedInteger('make_id')->index();
            $table->unsignedInteger('model_id')->index();
            $table->text('notes')->nullable();
            $table->enum('category', ['General', 'Sales'])->default('General');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->unsignedInteger('company_id')->index();
            $table->timestamps();
            $table->softDeletes();

            // vehicle that belong to the vehicle type
            $table->foreign('type_id')->references('id')->on('vehicle_types')->onDelete('cascade');

            // vehicle that belong to the vehicle make
            $table->foreign('make_id')->references('id')->on('vehicle_makes')->onDelete('cascade');

            // vehicle that belong to the vehicle model
            $table->foreign('model_id')->references('id')->on('vehicle_models')->onDelete('cascade');

            // vehicle that belong to the company
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
