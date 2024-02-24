<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_commissions', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');

            $table->string('year');
            $table->string('month');

            $table->double('credit_sales')->default(0);
            $table->double('cheque_received')->default(0);
            $table->double('cheque_collection_dr')->default(0);
            $table->double('cheque_returned')->default(0);
            $table->double('sales_returned')->default(0);
            $table->double('sales_target')->default(0);
            $table->double('special_target')->default(0);

            $table->double('total_sales')->default(0);
            $table->double('cash_collection')->default(0);
            $table->double('cheque_collection_cr')->default(0);
            $table->double('cheque_realized')->default(0);
            $table->double('customer_visited_count')->default(0);
            $table->double('customer_visited_rate')->default(0);
            $table->double('customer_visited')->default(0);

            $table->double('product_sold_count')->default(0);
            $table->double('product_sold_rate')->default(0);
            $table->double('product_sold')->default(0);

            $table->double('debit_balance')->default(0);
            $table->double('credit_balance')->default(0);

            $table->enum('status', ['Drafted', 'Approved'])->default('Drafted');
            $table->text('notes')->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->dateTime('prepared_on')->nullable();

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->dateTime('approved_on')->nullable();

            $table->unsignedInteger('staff_id')->index();
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps');

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
        Schema::dropIfExists('sales_commissions');
    }
}
