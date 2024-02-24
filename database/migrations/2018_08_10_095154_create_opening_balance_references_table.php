<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpeningBalanceReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opening_balance_references', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');

            $table->double('amount')->nullable();

            $table->enum('reference_type', ['Account', 'Customer', 'Supplier'])->nullable();

            $table->unsignedInteger('account_id')->index()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->string('reference_no')->nullable();

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->string('invoice_no', 50)->nullable();
            $table->date('invoice_date')->nullable();
            $table->double('invoice_amount')->nullable();
            $table->string('invoice_due', 50)->nullable();
            $table->string('invoice_due_age', 50)->nullable();

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->string('bill_no', 50)->nullable();
            $table->date('bill_date')->nullable();
            $table->double('bill_amount')->nullable();
            $table->string('bill_due', 50)->nullable();
            $table->string('bill_due_age', 50)->nullable();

            $table->unsignedInteger('updated_by')->index();
            $table->foreign('updated_by')->references('id')->on('users');

            $table->unsignedInteger('order_id')->index()->nullable();
            $table->foreign('order_id')->references('id')->on('sales_orders');

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
        Schema::dropIfExists('opening_balance_references');
    }
}
