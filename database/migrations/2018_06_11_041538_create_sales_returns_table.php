<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_returns', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code');

            $table->date('date');
            $table->text('notes')->nullable();

            $table->enum('status', ['Open', 'Closed', 'Processed'])->default('Open');

            $table->enum('is_printed', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

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
        Schema::dropIfExists('sales_returns');
    }
}
