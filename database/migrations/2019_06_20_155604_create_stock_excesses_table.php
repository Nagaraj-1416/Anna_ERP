<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockExcessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_excesses', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');

            $table->double('amount')->default(0);

            $table->enum('status', ['Drafted', 'Approved', 'Rejected'])->default('Drafted');
            $table->text('notes')->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->dateTime('prepared_on')->nullable();

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->dateTime('approved_on')->nullable();

            $table->unsignedInteger('route_id')->index();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('staff_id')->index();
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->unsignedInteger('daily_sale_id')->index();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('sales_handover_id')->index();
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');

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
        Schema::dropIfExists('stock_excesses');
    }
}
