<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashBreakdownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_breakdowns', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');
            $table->string('rupee_type');
            $table->integer('count');

            $table->unsignedInteger('sales_handover_id')->index()->nullable();
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

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
        Schema::dropIfExists('cash_breakdowns');
    }
}
