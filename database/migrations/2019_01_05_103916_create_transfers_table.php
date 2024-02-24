<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', ['Cash', 'Cheque']);
            $table->dateTime('date');
            $table->double('amount')->default(0);

            $table->unsignedInteger('transfer_by');
            $table->foreign('transfer_by')->references('id')->on('users');

            $table->unsignedInteger('sender');
            $table->foreign('sender')->references('id')->on('companies');

            $table->unsignedInteger('receiver');
            $table->foreign('receiver')->references('id')->on('companies');

            $table->unsignedInteger('credited_to');
            $table->foreign('credited_to')->references('id')->on('accounts');

            $table->unsignedInteger('debited_to');
            $table->foreign('debited_to')->references('id')->on('accounts');

            $table->enum('status', ['Drafted', 'Pending', 'Partially Received', 'Received', 'Declined'])->default('Pending');

            $table->unsignedInteger('received_by')->index()->nullable();
            $table->foreign('received_by')->references('id')->on('users');

            $table->dateTime('received_on')->nullable();

            $table->double('received_amount')->default(0)->nullable();

            $table->unsignedInteger('transaction_id')->index()->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions');

            $table->text('notes')->nullable();

            $table->enum('transfer_mode', ['ByHand', 'DepositedToBank'])->nullable();

            $table->date('handed_over_date')->nullable();
            $table->time('handed_over_time')->nullable();

            $table->unsignedInteger('handed_order_to')->index()->nullable();
            $table->foreign('handed_order_to')->references('id')->on('staff');

            $table->date('deposited_date')->nullable();
            $table->time('deposited_time')->nullable();

            $table->unsignedInteger('deposited_to')->index()->nullable();
            $table->foreign('deposited_to')->references('id')->on('accounts');

            $table->string('deposited_receipt')->nullable();

            $table->dateTime('receipt_uploaded_on')->nullable();

            $table->unsignedInteger('receipt_uploaded_by')->index()->nullable();
            $table->foreign('receipt_uploaded_by')->references('id')->on('users');

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
        Schema::dropIfExists('transfers');
    }
}
