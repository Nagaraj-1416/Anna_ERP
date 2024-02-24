<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_cheques', function (Blueprint $table) {
            $table->increments('id');

            $table->double('amount')->default(0);

            $table->unsignedInteger('expense_payment_id')->index()->nullable();
            $table->foreign('expense_payment_id')->references('id')->on('expense_payments');

            $table->unsignedInteger('cheque_in_hand_id')->index()->nullable();
            $table->foreign('cheque_in_hand_id')->references('id')->on('cheque_in_hands');

            $table->unsignedInteger('expense_id')->index()->nullable();
            $table->foreign('expense_id')->references('id')->on('expenses');

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
        Schema::dropIfExists('expense_cheques');
    }
}
