<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailySalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_sales', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();

            $table->enum('day_type', ['Single', 'Multiple']);
            $table->date('from_date');
            $table->date('to_date');
            $table->string('days', 50);

            $table->enum('sales_location', ['Van', 'Shop', 'Other']);

            $table->unsignedInteger('sales_location_id')->index()->nullable();
            $table->foreign('sales_location_id')->references('id')->on('sales_locations');

            /** if van */
            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->unsignedInteger('rep_id')->index()->nullable();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->text('notes')->nullable();

            $table->enum('status', ['Draft', 'Active', 'Progress', 'Completed', 'Canceled'])->default('Active');

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('company_id')->index()->nullable();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->enum('is_logged_in', ['Yes', 'No'])->default('No')->nullable();
            $table->enum('is_logged_out', ['Yes', 'No'])->default('No')->nullable();
            $table->timestampTz('logged_in_at')->nullable();
            $table->timestampTz('logged_out_at')->nullable();

            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->unsignedInteger('nxt_day_al_route')->index()->nullable();
            $table->foreign('nxt_day_al_route')->references('id')->on('routes');

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
        Schema::dropIfExists('daily_sales');
    }
}
