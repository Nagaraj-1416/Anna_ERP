<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuedChequesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issued_cheques', function (Blueprint $table) {
            $table->increments('id');

            $table->date('registered_date');
            $table->enum('type', ['Auto', 'Manual'])->default('Auto');
            $table->double('amount')->default(0);

            $table->date('cheque_date');
            $table->string('cheque_no');
            $table->unsignedInteger('bank_id')->index()->nullable();
            $table->foreign('bank_id')->references('id')->on('banks');

            $table->unsignedInteger('chequeable_id')->nullable();
            $table->string('chequeable_type')->nullable();

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->text('notes')->nullable();

            $table->enum('status', ['Not Realised', 'Deposited', 'Realised', 'Bounced', 'Canceled'])->default('Not Realised');

            $table->unsignedInteger('credited_to')->index()->nullable();
            $table->foreign('credited_to')->references('id')->on('accounts');

            $table->unsignedInteger('deposited_to')->index()->nullable();
            $table->foreign('deposited_to')->references('id')->on('accounts');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('company_id')->index()->nullable();
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
        Schema::dropIfExists('issued_cheques');
    }
}
