<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('salutation', 100)->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('full_name', 100)->nullable();
            $table->string('tamil_name', 100)->nullable();
            $table->string('display_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website')->nullable();
            $table->enum('type', ['Internal', 'External']);
            $table->string('gps_lat', 100)->nullable();
            $table->string('gps_long', 100)->nullable();

            $table->double('cl_amount')->default(0)->nullable();
            $table->double('cl_notify_rate')->default(0)->nullable();

            $table->text('notes')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->string('customer_logo')->nullable();

            $table->unsignedInteger('route_id')->index()->nullable();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedInteger('location_id')->index()->nullable();
            $table->foreign('location_id')->references('id')->on('locations');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->double('opening_balance')->nullable();
            $table->date('opening_balance_at')->nullable();
            $table->enum('opening_balance_type', ['Debit','Credit'])->default('Debit');

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
        Schema::dropIfExists('customers');
    }
}