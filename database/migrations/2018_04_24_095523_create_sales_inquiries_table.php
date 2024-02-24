<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesInquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_inquiries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 100);
            $table->date('inquiry_date');

            $table->unsignedInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('prepared_by')->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->text('description')->nullable();

            $table->unsignedInteger('business_type_id')->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->enum('status', ['Open', 'Converted to Estimate', 'Converted to Order'])->default('Open');

            $table->string('converted_type')->nullable();
            $table->string('converted_id')->nullable();
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
        Schema::dropIfExists('sales_inquiries');
    }
}
