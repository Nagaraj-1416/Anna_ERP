<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('company_id')->index();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->enum('is_selling_price', ['Yes', 'No'])->default('Yes')->nullable();
            $table->enum('type', ['Shop', 'Sales Van', 'Other']);

            $table->unsignedInteger('vehicle_id')->index()->nullable();
            $table->foreign('vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

            // department associated with a company
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_locations');
    }
}
