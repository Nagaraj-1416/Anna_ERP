<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChequeInHandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cheque_in_hands', function (Blueprint $table) {
            $table->increments('id');

            $table->date('registered_date');
            $table->enum('type', ['Auto', 'Manual'])->default('Auto');
            $table->double('amount')->default(0);

            $table->enum('cheque_type', ['Own', 'Third Party'])->default('Own')->nullable();
            $table->date('cheque_date');
            $table->string('cheque_no');
            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->unsignedInteger('chequeable_id')->nullable();
            $table->string('chequeable_type')->nullable();

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('daily_sale_id')->index()->nullable();
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('sales_handover_id')->index()->nullable();
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');

            $table->text('notes')->nullable();

            $table->enum('status', ['Not Realised', 'Deposited', 'Realised', 'Bounced', 'Canceled'])->default('Not Realised');

            $table->unsignedInteger('credited_to')->index()->nullable();
            $table->foreign('credited_to')->references('id')->on('accounts');

            $table->unsignedInteger('deposited_to')->index()->nullable();
            $table->foreign('deposited_to')->references('id')->on('accounts');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('business_type_id')->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');

            $table->unsignedInteger('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->enum('is_transferred', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('transferred_from')->index()->nullable();
            $table->foreign('transferred_from')->references('id')->on('accounts');

            $table->unsignedInteger('transferred_to')->index()->nullable();
            $table->foreign('transferred_to')->references('id')->on('accounts');

            $table->enum('settled', ['Yes', 'No'])->default('No');

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
        Schema::dropIfExists('cheque_in_hands');
    }
}
