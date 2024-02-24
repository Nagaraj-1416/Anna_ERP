<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');

            $table->date('date');

            $table->double('items')->default(0);
            $table->double('total')->default(0);

            $table->text('notes')->nullable();

            $table->enum('category', ['Production', 'Store', 'Shop']);

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('unit_id')->index()->nullable();
            $table->foreign('unit_id')->references('id')->on('production_units');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('sales_locations');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->dateTime('prepared_on')->nullable();

            $table->enum('is_approved', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->dateTime('approved_on')->nullable();

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
        Schema::dropIfExists('purchase_returns');
    }
}
