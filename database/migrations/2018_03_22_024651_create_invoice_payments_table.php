<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->double('payment')->default(0);
            $table->date('payment_date');

            $table->enum('payment_type', ['Advanced', 'Partial Payment', 'Final Payment'])->default(null);
            $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card', 'Customer Credit'])->default('Cash');

            /** if payment mode is Cheque */
            $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();
            $table->string('cheque_no', 50)->nullable();
            $table->date('cheque_date')->nullable();
            $table->enum('is_cheque_realized', ['Yes', 'No'])->default('No')->nullable();

            /** if payment mode is Direct Deposit */
            $table->string('account_no', 50)->nullable();
            $table->date('deposited_date')->nullable();

            /** if payment mode is Credit Card */
            $table->string('card_holder_name')->nullable();
            $table->string('card_no')->nullable();
            $table->date('expiry_date')->nullable();

            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->enum('status', ['Paid', 'Canceled', 'Refunded', 'Deleted'])->default('Paid');

            $table->text('notes')->nullable();

            $table->enum('payment_from', ['Direct', 'Credit'])->default('Direct');

            /** cancel and refund process related fields */
            $table->unsignedInteger('refunded_from')->index()->nullable();
            $table->foreign('refunded_from')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedInteger('credit_id')->index()->nullable();
            $table->foreign('credit_id')->references('id')->on('customer_credits');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('deposited_to')->index()->nullable();
            $table->foreign('deposited_to')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedInteger('invoice_id')->index()->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

            $table->unsignedInteger('sales_order_id')->index()->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('business_type_id')->index()->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');
            $table->string('uuid')->nullable();

            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();

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
        Schema::dropIfExists('invoice_payments');
    }
}
