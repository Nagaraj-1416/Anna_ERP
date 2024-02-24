<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepVehicleHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rep_vehicle_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vehicle_id')->index();
            $table->unsignedInteger('rep_id')->index();
            $table->date('assigned_date')->nullable();
            $table->date('revoked_date')->nullable();
            $table->date('blocked_date')->nullable();
            $table->enum('status', ['Active', 'Revoked', 'Blocked']);
            $table->timestamps();
            $table->softDeletes();

//            Define foreign key
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('rep_id')->references('id')->on('reps')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rep_vehicle_histories');
    }
}
