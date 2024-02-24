<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_return_items', function (Blueprint $table) {
            $table->increments('id');

            $table->double('ordered_qty')->default(0);
            $table->double('returned_qty')->default(0);

            $table->double('ordered_rate')->default(0);
            $table->double('returned_rate')->default(0);

            $table->double('order_amount')->default(0);
            $table->double('returned_amount')->default(0);

            $table->string('reason');

            $table->unsignedInteger('purchase_return_id')->index()->nullable();
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns');

            $table->unsignedInteger('order_id')->index()->nullable();
            $table->foreign('order_id')->references('id')->on('purchase_orders');

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

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
        Schema::dropIfExists('purchase_return_items');
    }
}
