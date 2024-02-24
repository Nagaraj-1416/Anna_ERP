<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGrnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grns', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50);

            $table->date('date');

            $table->enum('grn_for', ['PUnit', 'Store', 'Shop'])->nullable();

            $table->text('notes')->nullable();
            $table->enum('status', ['Drafted', 'Sent', 'Partially Received', 'Received'])->default('Drafted');

            $table->enum('transfer_by', ['Internal', 'OwnVehicle', 'HiredVehicle']);

            /** if transfer by Own Vehicle */
            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->string('odo_starts_at', 20)->nullable();
            $table->string('odo_ends_at', 20)->nullable();

            $table->unsignedInteger('driver')->index()->nullable();
            $table->foreign('driver')->references('id')->on('staff');

            $table->unsignedInteger('helper')->index()->nullable();
            $table->foreign('helper')->references('id')->on('staff');
            /** END */

            /** if transfer by Hired Vehicle */
            $table->string('vehicle_no', 50)->nullable();
            $table->string('transport_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('helper_name')->nullable();
            /** END */

            $table->string('loaded_by')->nullable();
            $table->string('unloaded_by')->nullable();

            $table->dateTime('trans_started_at')->nullable();
            $table->dateTime('trans_ended_at')->nullable();

            $table->unsignedInteger('received_by')->index()->nullable();
            $table->foreign('received_by')->references('id')->on('users');

            $table->unsignedInteger('parent_grn')->index()->nullable();
            $table->foreign('parent_grn')->references('id')->on('grns');

            $table->unsignedInteger('purchase_order_id')->index();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('production_unit_id')->index()->nullable();
            $table->foreign('production_unit_id')->references('id')->on('production_units');

            $table->unsignedInteger('store_id')->index()->nullable();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('shop_id')->index()->nullable();
            $table->foreign('shop_id')->references('id')->on('sales_locations');

            $table->unsignedInteger('supplier_id')->index();
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
        Schema::dropIfExists('grns');
    }
}
