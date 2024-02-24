<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grn_items', function (Blueprint $table) {
            $table->increments('id');

            $table->double('quantity')->default(0);
            $table->double('issued_qty')->default(0);
            $table->double('pending_qty')->default(0);
            $table->double('received_qty')->default(0);
            $table->double('rejected_qty')->default(0);

            $table->double('rate')->default(0);
            $table->double('amount')->default(0);

            $table->date('manufacture_date')->nullable();
            $table->date('expiry_date')->nullable();

            $table->string('batch_no')->nullable();
            $table->string('grade')->nullable();
            $table->string('color')->nullable();
            $table->string('packing_type')->nullable();
            $table->string('brand')->nullable();

            $table->enum('status', ['Sent', 'Partially Received', 'Received', 'Damaged', 'Returned', 'Canceled'])->default('Sent');

            $table->unsignedInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedInteger('grn_id')->index();
            $table->foreign('grn_id')->references('id')->on('grns');

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
        Schema::dropIfExists('grn_items');
    }
}
