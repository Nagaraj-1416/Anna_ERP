<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');

            $table->string('po_no', 50);

            $table->date('order_date');
            $table->date('delivery_date')->nullable();

            $table->enum('po_type', ['Auto', 'Manual']);
            $table->enum('po_mode', ['Internal', 'External', 'Virtual'])->nullable();

            $table->enum('po_for', ['PUnit', 'Store', 'Shop'])->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['Drafted', 'Pending', 'Sent', 'Delivered', 'Canceled'])->default('Drafted');

            $table->enum('grn_created', ['Yes', 'No'])->default('No');
            $table->enum('grn_received', ['Yes', 'No'])->default('No');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('production_unit_id')->index()->nullable();
            $table->foreign('production_unit_id')->references('id')->on('production_units');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('sales_locations');

            $table->unsignedInteger('supplier_id')->index()->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

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
        Schema::dropIfExists('purchase_orders');
    }
}
