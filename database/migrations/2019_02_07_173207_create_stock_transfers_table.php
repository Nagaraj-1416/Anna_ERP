<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('date');

            $table->unsignedInteger('transfer_by');
            $table->foreign('transfer_by')->references('id')->on('users');

            $table->unsignedInteger('vehicle_id');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->unsignedInteger('transfer_from');
            $table->foreign('transfer_from')->references('id')->on('stores');

            $table->unsignedInteger('transfer_to');
            $table->foreign('transfer_to')->references('id')->on('stores');

            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->enum('status', ['Drafted', 'Pending', 'Received', 'Declined'])->default('Pending');

            $table->text('notes')->nullable();

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
        Schema::dropIfExists('stock_transfers');
    }
}
