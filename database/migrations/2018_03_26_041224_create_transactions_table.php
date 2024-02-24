<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->nullable();
            $table->date('date');
            $table->enum('category', ['Auto', 'Manual']);
            $table->enum('type', ['Deposit', 'Withdrawal']);
            $table->double('amount')->default(0);
            $table->string('auto_narration')->nullable();
            $table->string('manual_narration')->nullable();
            $table->text('notes')->nullable();
            $table->string('action', 50)->nullable();

            $table->unsignedInteger('tx_type_id')->index();
            $table->foreign('tx_type_id')->references('id')->on('transaction_types')->onDelete('cascade');

            $table->unsignedInteger('transactionable_id')->nullable();
            $table->string('transactionable_type')->nullable();

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('business_type_id')->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');

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
        Schema::dropIfExists('transactions');
    }
}
