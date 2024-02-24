<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesHandoverShortagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_handover_shortages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->unsignedInteger('sales_handover_id')->index()->nullable();
            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->date('date')->nullable();
            $table->double('amount')->nullable();
            $table->unsignedInteger('submitted_by')->index()->nullable();
            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->date('approved_at')->nullable();
            $table->unsignedInteger('rejected_by')->index()->nullable();
            $table->date('rejected_at')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');
            $table->foreign('rep_id')->references('id')->on('reps');
            $table->foreign('submitted_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
            $table->foreign('rejected_by')->references('id')->on('users');
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
        Schema::dropIfExists('sales_handover_shortages');
    }
}
