<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->double('payment')->default(0);
            $table->date('payment_date');

            $table->enum('payment_type', ['Advanced', 'Partial Payment', 'Final Payment'])->default(null);
            $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card', 'Special Discount', 'Exchange', 'Virtual'])->default('Cash');

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
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->enum('status', ['Paid', 'Canceled', 'Refunded', 'Deleted'])->default('Paid');

            $table->text('notes')->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('paid_through')->index()->nullable();
            $table->foreign('paid_through')->references('id')->on('accounts')->onDelete('cascade');

            $table->enum('payment_from', ['Direct', 'Credit'])->default('Direct');

            $table->unsignedInteger('credit_id')->index()->nullable();
            $table->foreign('credit_id')->references('id')->on('supplier_credits');

            $table->unsignedInteger('bill_id')->index()->nullable();
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');

            $table->unsignedInteger('purchase_order_id')->index()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            $table->unsignedInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('business_type_id')->index();
            $table->foreign('business_type_id')->references('id')->on('business_types');

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
        Schema::dropIfExists('bill_payments');
    }
}
