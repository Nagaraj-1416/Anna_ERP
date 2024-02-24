<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_items', function (Blueprint $table) {
            $table->increments('id');

            $table->double('qty')->default(0);
            $table->enum('type', ['Stock', 'Sales'])->default('Stock');
            $table->double('sold_rate')->default(0)->nullable();
            $table->double('returned_rate')->default(0)->nullable();
            $table->double('returned_amount')->default(0)->nullable();
            $table->string('reason');

            $table->unsignedInteger('sales_return_id')->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_returns');

            $table->unsignedInteger('order_id')->index()->nullable();
            $table->foreign('order_id')->references('id')->on('sales_orders');

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('returned_to')->index()->nullable();
            $table->foreign('returned_to')->references('id')->on('stores');

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('company_id')->index();
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
        Schema::dropIfExists('sales_return_items');
    }
}
