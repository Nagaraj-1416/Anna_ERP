<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySalesOdoReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_sales_odo_readings', function (Blueprint $table) {
            $table->increments('id');

            $table->double('starts_at')->nullable()->default(0);
            $table->double('ends_at')->nullable()->default(0);

            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('sales_handover_id')->index()->nullable();
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');

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
        Schema::dropIfExists('daily_sales_odo_readings');
    }
}
