<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('report_no', 50);
            $table->string('title');
            $table->date('report_from');
            $table->date('report_to');
            $table->text('notes')->nullable();
            $table->double('amount')->default(0);

            $table->enum('status', ['Draft', 'Submitted', 'Approved', 'Rejected', 'Partially Reimbursed', 'Reimbursed'])->default('Draft');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('business_type_id')->index();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->date('submitted_on')->nullable();

            $table->unsignedInteger('submitted_by')->index()->nullable();
            $table->foreign('submitted_by')->references('id')->on('users');

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
        Schema::dropIfExists('expense_reports');
    }
}
