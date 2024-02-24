<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_product', function (Blueprint $table) {
            $table->unsignedInteger('estimate_id')->index();
            $table->foreign('estimate_id')->references('id')->on('estimates');

            $table->unsignedInteger('price_book_id')->index()->nullable();
            $table->foreign('price_book_id')->references('id')->on('price_books');

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->enum('discount_type', ['Amount', 'Percentage']);
            $table->double('discount_rate')->default(0);
            $table->double('discount')->default(0);
            $table->double('amount')->default(0);

            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_product');
    }
}
