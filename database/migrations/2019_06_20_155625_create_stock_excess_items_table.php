<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockExcessItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_excess_items', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');

            $table->double('qty')->default(0);
            $table->double('rate')->default(0);
            $table->double('amount')->default(0);

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('stock_id')->index()->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('stock_excess_id')->index()->nullable();
            $table->foreign('stock_excess_id')->references('id')->on('stock_excesses');

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
        Schema::dropIfExists('stock_excess_items');
    }
}
