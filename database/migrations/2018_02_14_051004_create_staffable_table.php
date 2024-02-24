<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffable', function (Blueprint $table) {
            $table->unsignedInteger('staff_id')->index();
            $table->unsignedInteger('staffable_id')->index();
            $table->string('staffable_type');
            $table->enum('is_head', ['Yes', 'No'])->default('No');
            $table->enum('is_default', ['Yes', 'No'])->default('No');
            // staffable associated with a staff
            $table->foreign('staff_id')->references('id')->on('staff')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staffable');
    }
}
