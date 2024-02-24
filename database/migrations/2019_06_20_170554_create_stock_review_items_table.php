<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockReviewItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_review_items', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');

            $table->double('available_qty')->default(0);
            $table->double('actual_qty')->default(0);
            $table->double('excess_qty')->default(0);
            $table->double('shortage_qty')->default(0);

            $table->double('rate')->default(0);
            $table->double('amount')->default(0);
            $table->double('excess_amount')->default(0);
            $table->double('shortage_amount')->default(0);

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('stock_id')->index();
            $table->foreign('stock_id')->references('id')->on('stocks');

            $table->unsignedInteger('stock_review_id')->index();
            $table->foreign('stock_review_id')->references('id')->on('stock_reviews');

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
        Schema::dropIfExists('stock_review_items');
    }
}
