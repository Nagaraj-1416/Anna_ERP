<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stock_id')->index()->nullable();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->string('quantity');
            $table->enum('transaction', ['In', 'Out']);
            $table->date('trans_date');
            $table->text('trans_description')->nullable();

            $table->unsignedInteger('production_unit_id')->index()->nullable();
            $table->foreign('production_unit_id')->references('id')->on('production_units');

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');

            $table->double('rate')->default(0)->nullable();

            $table->enum('type', ['Opening','Purchase','Sale','Taken','Transfer','Restore'])->nullable();

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

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
        Schema::dropIfExists('stock_histories');
    }
}
