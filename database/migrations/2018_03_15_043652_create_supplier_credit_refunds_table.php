<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierCreditRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_credit_refunds', function (Blueprint $table) {
            $table->increments('id');

            $table->date('refunded_on');
            $table->float('amount')->default(0);
            $table->text('notes')->nullable();

            $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card'])->default('Cash');

            /** if payment mode is Cheque */
            $table->string('cheque_no', 50)->nullable();
            $table->date('cheque_date')->nullable();

            /** if payment mode is Direct Deposit */
            $table->string('account_no', 50)->nullable();
            $table->date('deposited_date')->nullable();

            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->enum('status', ['Paid', 'Canceled'])->default('Paid');

            $table->text('reason_to_cancel')->nullable();

            $table->unsignedInteger('refunded_to')->index();
            $table->foreign('refunded_to')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedInteger('credit_id')->index()->nullable();
            $table->foreign('credit_id')->references('id')->on('supplier_credits')->onDelete('cascade');

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
        Schema::dropIfExists('supplier_credit_refunds');
    }
}
