<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('pre_allocation_id')->index()->nullable();
            $table->foreign('pre_allocation_id')->references('id')->on('daily_sales');

            $table->enum('sales_location', ['Van', 'Shop', 'Other']);

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->text('notes')->nullable();

            $table->enum('status', ['Pending', 'Allocated', 'Canceled'])->default('Pending');

            $table->unsignedInteger('company_id')->index()->nullable();
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
        Schema::dropIfExists('daily_stocks');
    }
}
