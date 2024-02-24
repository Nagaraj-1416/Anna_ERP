<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_no', 50);
            $table->string('ref', 50);
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->enum('order_type', ['Direct', 'Schedule'])->default('Direct');
            $table->date('scheduled_date')->nullable();

            $table->enum('order_mode', ['Customer', 'Cash'])->default('Customer');
            $table->enum('is_credit_sales', ['Yes', 'No'])->default('No');

            $table->enum('is_po_received', ['Yes', 'No'])->default('No');
            $table->string('po_no', 50)->nullable();
            $table->date('po_date')->nullable();
            $table->string('po_file')->nullable();

            $table->text('terms')->nullable();
            $table->text('notes')->nullable();
            $table->double('sub_total')->default(0);
            $table->double('discount')->default(0);
            $table->double('discount_rate')->default(0);
            $table->enum('discount_type', ['Amount', 'Percentage']);
            $table->double('adjustment')->default(0);
            $table->double('total')->default(0);

            $table->enum('status', ['Scheduled', 'Draft', 'Awaiting Approval', 'Open', 'Closed', 'Canceled'])->default('Awaiting Approval');
            $table->enum('delivery_status', ['Pending', 'Partially Delivered', 'Delivered', 'Canceled'])->default('Pending');
            $table->enum('invoice_status', ['Pending', 'Partially Invoiced', 'Invoiced'])->default('Pending');

            $table->enum('is_invoiced', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->unsignedInteger('customer_id')->index()->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('business_type_id')->index()->nullable();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->enum('sales_type', ['Retail', 'Wholesale', 'Distribution'])->default('Retail');

            $table->unsignedInteger('price_book_id')->index()->nullable();
            $table->foreign('price_book_id')->references('id')->on('price_books');

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');

            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();

            $table->enum('is_order_printed', ['Yes', 'No'])->default('No');

            $table->enum('sales_category', ['Office', 'Shop', 'Van']);
            $table->double('distance')->nullable();

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('location_id')->index()->nullable();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->enum('is_opining', ['Yes', 'No'])->default('No');

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
        Schema::dropIfExists('sales_orders');
    }
}
