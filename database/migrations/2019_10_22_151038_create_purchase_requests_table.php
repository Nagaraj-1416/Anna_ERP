<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('request_no', 50);

            $table->date('request_date');

            $table->enum('request_type', ['Auto', 'Manual']);
            $table->enum('request_mode', ['Internal', 'External', 'Virtual'])->nullable();

            $table->enum('request_for', ['PUnit', 'Store', 'Shop'])->nullable();

            $table->text('notes')->nullable();

            $table->enum('status', ['Drafted', 'Completed'])->default('Drafted');

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
        Schema::dropIfExists('purchase_requests');
    }
}
