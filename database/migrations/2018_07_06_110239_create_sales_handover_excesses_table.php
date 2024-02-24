<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesHandoverExcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_handover_excesses', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->unsignedInteger('sales_handover_id')->index()->nullable();
            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->unsignedInteger('submitted_by')->index()->nullable();

            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');
            $table->foreign('rep_id')->references('id')->on('reps');
            $table->foreign('submitted_by')->references('id')->on('users');
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
        Schema::dropIfExists('sales_handover_excesses');
    }
}
