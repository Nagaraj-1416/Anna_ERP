<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->double('payment')->default(0);
            $table->date('payment_date');

            $table->enum('payment_mode', ['Cash', 'Bank', 'Own Cheque', 'Third Party Cheque'])->default('Cash');

            /** if payment mode is Cheque */
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

            $table->enum('status', ['Paid', 'Canceled'])->default('Paid');

            $table->text('notes')->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('paid_through')->index()->nullable();
            $table->foreign('paid_through')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedInteger('expense_id')->index();
            $table->foreign('expense_id')->references('id')->on('expenses');

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
        Schema::dropIfExists('expense_payments');
    }
}
