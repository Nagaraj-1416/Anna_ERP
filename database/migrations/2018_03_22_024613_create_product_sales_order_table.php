<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSalesOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sales_order', function (Blueprint $table) {
            $table->unsignedInteger('sales_order_id')->index();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders');

            $table->unsignedInteger('price_book_id')->index()->nullable();
            $table->foreign('price_book_id')->references('id')->on('price_books');

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->enum('is_vehicle', ['Yes', 'No'])->default('No');

            $table->double('quantity')->default(0);
            $table->double('rate')->default(0);
            $table->enum('discount_type', ['Amount', 'Percentage']);
            $table->double('discount_rate')->default(0);
            $table->double('discount')->default(0);
            $table->double('amount')->default(0);

            $table->enum('status', ['Pending', 'Partially Delivered', 'Delivered', 'Canceled'])->default('Pending');
            $table->text('notes')->nullable();

            $table->unsignedInteger('unit_type_id')->index()->nullable();
            $table->foreign('unit_type_id')->references('id')->on('unit_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sales_order');
    }
}
