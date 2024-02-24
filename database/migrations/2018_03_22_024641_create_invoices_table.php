<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_no', 50);
            $table->string('ref', 50);
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->enum('invoice_type', ['Proforma Invoice', 'Invoice'])->default('Invoice');

            $table->double('amount')->default(0);

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');

            $table->enum('status', ['Draft', 'Open', 'Overdue', 'Partially Paid', 'Paid', 'Canceled', 'Refunded'])->default('Open');

            $table->text('notes')->nullable();

            $table->unsignedInteger('sales_order_id')->index()->nullable();
            $table->foreign('sales_order_id')->references('id')->on('sales_orders')->onDelete('cascade');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('business_type_id')->index()->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');

            $table->string('uuid')->nullable();

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
        Schema::dropIfExists('invoices');
    }
}
