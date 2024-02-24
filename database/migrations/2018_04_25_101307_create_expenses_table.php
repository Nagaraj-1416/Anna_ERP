<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('expense_no', 50);
            $table->date('expense_date');
            $table->enum('claim_reimburse', ['Yes', 'No'])->default('Yes');
            $table->enum('expense_items', ['Single', 'Multiple'])->default(null);
            $table->enum('calculate_mileage_using', ['Distance', 'Odometer'])->default(null);
            $table->text('notes')->nullable();
            $table->double('amount')->default(0);

            $table->double('distance')->default(0);
            $table->double('start_reading')->default(0);
            $table->double('end_reading')->default(0);

            $table->enum('status', ['Unreported', 'Unsubmitted', 'Submitted', 'Approved', 'Rejected', 'Reimbursed'])->default('Unreported');

            $table->unsignedInteger('type_id')->index()->nullable();
            $table->foreign('type_id')->references('id')->on('expense_types');

            $table->unsignedInteger('expense_account')->index()->nullable();
            $table->foreign('expense_account')->references('id')->on('accounts');

            $table->unsignedInteger('paid_through')->index()->nullable();
            $table->foreign('paid_through')->references('id')->on('accounts');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('business_type_id')->index()->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('staff_id')->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->unsignedInteger('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('report_id')->index()->nullable();
            $table->foreign('report_id')->references('id')->on('expense_reports');

            $table->enum('payment_mode', ['Cash', 'Cheque', 'Direct Deposit', 'Credit Card'])->default('Cash');

            /** if payment mode is Cheque */
            $table->string('cheque_no', 50)->nullable();
            $table->date('cheque_date')->nullable();

            /** if payment mode is Direct Deposit */
            $table->string('account_no', 50)->nullable();
            $table->date('deposited_date')->nullable();

            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();
            $table->double('liter', 20)->nullable()->default(0);
            $table->double('odometer', 20)->nullable()->default(0);

            $table->unsignedInteger('sales_expense_id')->index()->nullable();
            $table->foreign('sales_expense_id')->references('id')->on('sales_expenses')->onDelete('cascade');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
