<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleRepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_rep', function (Blueprint $table) {
            $table->unsignedInteger('vehicle_id')->index();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps')->onDelete('cascade');
            $table->date('assigned_date')->nullable();
            $table->date('revoked_date')->nullable();
            $table->date('blocked_date')->nullable();
            $table->enum('status', ['Assigned', 'Revoked', 'Blocked']);
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
        Schema::dropIfExists('vehicle_rep');
    }
}
