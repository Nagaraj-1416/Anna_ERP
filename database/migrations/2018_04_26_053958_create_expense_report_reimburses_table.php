<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseReportReimbursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_report_reimburses', function (Blueprint $table) {
            $table->increments('id');

            $table->date('reimbursed_on');
            $table->text('notes')->nullable();
            $table->double('amount')->default(0);

            $table->unsignedInteger('paid_through')->index();
            $table->foreign('paid_through')->references('id')->on('accounts');

            $table->unsignedInteger('report_id')->index();
            $table->foreign('report_id')->references('id')->on('expense_reports');

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
        Schema::dropIfExists('expense_report_reimburses');
    }
}
