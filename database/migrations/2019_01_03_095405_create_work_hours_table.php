<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_hours', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->date('date');
            $table->time('start');
            $table->time('end');

            $table->enum('status', ['Allocated', 'Closed', 'Terminated']);

            $table->unsignedInteger('allocated_by')->index()->nullable();
            $table->foreign('allocated_by')->references('id')->on('users');

            $table->unsignedInteger('terminated_by')->index()->nullable();
            $table->foreign('terminated_by')->references('id')->on('users');

            $table->unsignedInteger('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

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
        Schema::dropIfExists('work_hours');
    }
}
