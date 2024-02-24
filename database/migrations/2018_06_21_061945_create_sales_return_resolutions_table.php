<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_return_resolutions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('resolution', ['Refund', 'Credit', 'Replace'])->default('Refund');
            $table->double('amount')->default(0)->nullable();
            $table->unsignedInteger('sales_return_id')->index()->nullable();
            $table->foreign('sales_return_id')->references('id')->on('sales_returns');
            $table->unsignedInteger('order_id')->index()->nullable();
            $table->foreign('order_id')->references('id')->on('sales_orders');
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
        Schema::dropIfExists('sales_return_resolutions');
    }
}
