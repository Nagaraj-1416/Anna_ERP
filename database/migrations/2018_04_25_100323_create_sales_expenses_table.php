<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->date('expense_date');
            $table->time('expense_time')->nullable();
            $table->enum('calculate_mileage_using', ['Distance', 'Odometer'])->nullable()->default('Distance');
            $table->text('notes')->nullable();
            $table->double('amount')->default(0);

            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();
            $table->double('liter', 20)->nullable()->default(0);
            $table->double('odometer', 20)->nullable()->default(0);

            $table->double('distance')->nullable()->default(0);
            $table->double('start_reading')->nullable()->default(0);
            $table->double('end_reading')->nullable()->default(0);

            $table->enum('status', ['Submitted', 'Approved', 'Rejected'])->default('Submitted');


            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');


            $table->unsignedInteger('staff_id')->index()->nullable();
            $table->foreign('staff_id')->references('id')->on('staff');

            $table->unsignedInteger('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('daily_sale_id');
            $table->foreign('daily_sale_id')->references('id')->on('daily_sales');

            $table->unsignedInteger('sales_handover_id');
            $table->foreign('sales_handover_id')->references('id')->on('sales_handovers');

            $table->unsignedInteger('type_id')->index()->nullable();
            $table->foreign('type_id')->references('id')->on('expense_types');

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
        Schema::dropIfExists('sales_expenses');
    }
}
