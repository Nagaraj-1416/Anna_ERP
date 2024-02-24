<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceHistoryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_history_items', function (Blueprint $table) {
            $table->increments('id');
            $table->double('price')->default(0);
            $table->unsignedInteger('range_start_from')->nullable();
            $table->unsignedInteger('range_end_to')->nullable();
            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unsignedInteger('price_book_id')->index();
            $table->foreign('price_book_id')->references('id')->on('price_books')->onDelete('cascade');
            $table->unsignedInteger('price_history_id')->index();
            $table->foreign('price_history_id')->references('id')->on('price_histories')->onDelete('cascade');
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
        Schema::dropIfExists('price_history_items');
    }
}
