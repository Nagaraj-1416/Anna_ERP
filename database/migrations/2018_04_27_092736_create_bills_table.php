<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');

            $table->string('bill_no', 50);
            $table->date('bill_date');
            $table->date('due_date')->nullable();

            $table->float('amount')->default(0);

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users')->onDelete('cascade');

            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');

            $table->enum('status', ['Draft', 'Open', 'Overdue', 'Partially Paid', 'Paid', 'Canceled'])->default('Open');

            $table->text('notes')->nullable();

            $table->unsignedInteger('purchase_order_id')->index()->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');

            $table->unsignedInteger('grn_id')->index()->nullable();
            $table->foreign('grn_id')->references('id')->on('grns');

            $table->unsignedInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('store_id')->index();
            $table->foreign('store_id')->references('id')->on('stores');

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
        Schema::dropIfExists('bills');
    }
}
