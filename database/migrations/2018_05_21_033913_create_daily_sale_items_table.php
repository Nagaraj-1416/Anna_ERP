<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySaleItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_sale_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('daily_sale_id');
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');

            $table->double('quantity');
            $table->double('sold_qty')->nullable();
            $table->double('replaced_qty')->nullable();
            $table->double('restored_qty')->nullable();


            $table->double('returned_qty')->nullable();
            $table->double('shortage_qty')->nullable();
            $table->double('damaged_qty')->nullable();
            $table->double('excess_qty')->nullable();

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('daily_sale_items');
    }
}
