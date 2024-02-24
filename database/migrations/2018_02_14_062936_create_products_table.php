<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->string('tamil_name', 100);
            $table->string('measurement', 100);

            $table->enum('type', ['Raw Material', 'Finished Good', 'Third Party Product']);

            $table->double('buying_price')->nullable();

            $table->double('packet_price')->nullable();

            $table->unsignedInteger('expense_account')->index()->nullable();
            $table->foreign('expense_account')->references('id')->on('accounts')->onDelete('cascade');

            $table->double('wholesale_price')->nullable();
            $table->double('retail_price')->nullable();
            $table->double('distribution_price')->nullable();
            $table->unsignedInteger('income_account')->index()->nullable();
            $table->foreign('income_account')->references('id')->on('accounts')->onDelete('cascade');

            $table->string('min_stock_level', 10);
            $table->unsignedInteger('inventory_account')->index()->nullable();
            $table->foreign('inventory_account')->references('id')->on('accounts')->onDelete('cascade');

            $table->text('notes')->nullable();
            $table->string('product_image')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

            $table->enum('is_expirable', ['Yes', 'No'])->default('Yes');

            $table->double('opening_cost')->nullable();
            $table->date('opening_cost_at')->nullable();

            $table->unsignedInteger('opening_qty')->nullable();
            $table->date('opening_qty_at')->nullable();

            $table->unsignedInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('barcode_number')->nullable();
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
}
