<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyStockItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stock_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_stock_id');
            $table->foreign('daily_stock_id')->references('id')->on('daily_stocks');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->double('available_qty')->nullable();
            $table->double('default_qty')->nullable();
            $table->double('required_qty')->nullable();
            $table->double('issued_qty')->nullable();
            $table->double('pending_qty')->nullable();

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
        Schema::dropIfExists('daily_stock_items');
    }
}
