<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('cheque');

            $table->double('payment')->default(0);
            $table->date('payment_date');

            $table->enum('payment_type', ['Advanced', 'Partial Payment', 'Final Payment'])->default(null);
            $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card'])->default('Cash');

            /** if payment mode is Cheque */
            $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();
            $table->string('cheque_no', 50)->nullable();
            $table->date('cheque_date')->nullable();

            /** if payment mode is Direct Deposit */
            $table->string('account_no', 50)->nullable();
            $table->date('deposited_date')->nullable();

            /** if payment mode is Credit Card */
            $table->string('card_holder_name')->nullable();
            $table->string('card_no')->nullable();
            $table->date('expiry_date')->nullable();

            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->enum('status', ['Paid', 'Canceled', 'Refunded', 'Deleted'])->default('Paid');

            $table->text('notes')->nullable();

            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('deposited_to')->index()->nullable();
            $table->foreign('deposited_to')->references('id')->on('accounts');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('invoice_id')->index()->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices');

            $table->unsignedInteger('sales_order_id')->index()->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('route_id')->index();
            $table->foreign('route_id')->references('id')->on('routes');

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
        Schema::dropIfExists('cheque_payments');
    }
}
