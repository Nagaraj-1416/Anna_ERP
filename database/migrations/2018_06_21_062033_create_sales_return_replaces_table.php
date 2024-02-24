<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnReplacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_replaces', function (Blueprint $table) {
            $table->increments('id');

            $table->double('qty')->default(0);
            $table->double('rate')->default(0)->nullable();
            $table->double('amount')->default(0)->nullable();

            $table->unsignedInteger('product_id')->index()->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('resolution_id')->index()->nullable();
            $table->foreign('resolution_id')->references('id')->on('sales_return_resolutions');

            $table->unsignedInteger('sales_return_id')->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_returns');

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
        Schema::dropIfExists('sales_return_replaces');
    }
}
