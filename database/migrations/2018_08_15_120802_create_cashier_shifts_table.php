<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashierShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->increments('id');

            $table->double('amount')->default(0)->nullable();

            $table->dateTime('shift_from')->nullable();
            $table->dateTime('shift_to')->nullable();

            $table->unsignedInteger('shifted_by')->index()->nullable();
            $table->foreign('shifted_by')->references('id')->on('staff');

            $table->unsignedInteger('shifted_to')->index()->nullable();
            $table->foreign('shifted_to')->references('id')->on('staff');

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
        Schema::dropIfExists('cashier_shifts');
    }
}
