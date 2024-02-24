<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesHandoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_handovers', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();
            $table->date('date');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            /** today collection details */
            $table->double('sales')->default(0)->nullable();
            $table->double('cash_sales')->default(0)->nullable();
            $table->double('cheque_sales')->default(0)->nullable();
            $table->double('deposit_sales')->default(0)->nullable();
            $table->double('card_sales')->default(0)->nullable();
            $table->double('credit_sales')->default(0)->nullable();

            /** old invoice collection details */
            $table->double('old_sales')->default(0)->nullable();
            $table->double('old_cash_sales')->default(0)->nullable();
            $table->double('old_cheque_sales')->default(0)->nullable();
            $table->double('old_deposit_sales')->default(0)->nullable();
            $table->double('old_card_sales')->default(0)->nullable();
            $table->double('old_credit_sales')->default(0)->nullable();

            $table->double('total_collect')->default(0)->nullable();
            $table->string('cheques_count', 10)->nullable();
            $table->double('total_expense')->default(0)->nullable();
            $table->double('allowance')->default(0)->nullable();
            $table->double('sales_commission')->default(0)->nullable();
            $table->double('shortage')->default(0)->nullable();
            $table->double('excess')->default(0)->nullable();

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->text('notes')->nullable();

            $table->enum('status', ['Pending', 'Confirmed'])->default('Pending');

            $table->enum('is_cashier_approved', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('cashier_id')->index()->nullable();
            $table->foreign('cashier_id')->references('id')->on('users');

            $table->enum('is_sk_approved', ['Yes', 'No'])->default('Yes');

            $table->unsignedInteger('sk_id')->index()->nullable();
            $table->foreign('sk_id')->references('id')->on('users');

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
        Schema::dropIfExists('sales_handovers');
    }
}
