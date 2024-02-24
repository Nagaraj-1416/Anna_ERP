<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SalesLocationProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_location_product', function (Blueprint $table) {
            $table->unsignedInteger('sales_location_id');
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->double('default_qty')->default(0)->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_location_product');
    }
}
