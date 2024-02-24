<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySaleCreditOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_sale_credit_orders', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->unsignedInteger('sales_order_id')->index()->nullable();

            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('sales_order_id')->references('id')->on('sales_orders');
            $table->enum('added_stage', ['First', 'Later'])->default('First');
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
        Schema::dropIfExists('daily_sale_credit_orders');
    }
}
