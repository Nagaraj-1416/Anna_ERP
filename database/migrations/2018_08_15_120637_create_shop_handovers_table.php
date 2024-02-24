<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopHandoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_handovers', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');

            $table->double('sales')->default(0)->nullable();
            $table->double('cash_sales')->default(0)->nullable();
            $table->double('cheque_sales')->default(0)->nullable();
            $table->double('deposit_sales')->default(0)->nullable();
            $table->double('card_sales')->default(0)->nullable();
            $table->double('credit_sales')->default(0)->nullable();

            $table->double('total_collect')->default(0)->nullable();
            $table->string('cheques_count', 10)->nullable();
            $table->double('total_expense')->default(0)->nullable();
            $table->double('allowance')->default(0)->nullable();
            $table->double('sales_commission')->default(0)->nullable();
            $table->double('shortage')->default(0)->nullable();
            $table->double('excess')->default(0)->nullable();

            $table->unsignedInteger('staff_id')->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Confirmed'])->default('Pending');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

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
        Schema::dropIfExists('shop_handovers');
    }
}
