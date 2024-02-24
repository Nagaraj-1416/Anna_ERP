<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_items', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('date');

            $table->unsignedInteger('transfer_id');
            $table->foreign('transfer_id')->references('id')->on('transfers');

            $table->double('amount')->default(0);

            $table->string('cheque_no', 50)->nullable();
            $table->date('cheque_date')->nullable();
            $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();

            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');

            $table->enum('status', ['Pending', 'Received', 'Declined'])->default('Pending');

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
        Schema::dropIfExists('transfer_items');
    }
}
