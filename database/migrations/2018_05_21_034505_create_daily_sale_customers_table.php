<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySaleCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_sale_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('daily_sale_id');
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->text('notes')->nullable();
            $table->enum('is_visited', ['Yes', 'No'])->default('No');
            $table->text('reason')->nullable();
            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();
            $table->double('distance')->nullable();
            $table->enum('added_stage', ['First', 'Later'])->default('First');
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
        Schema::dropIfExists('daily_sale_customers');
    }
}
