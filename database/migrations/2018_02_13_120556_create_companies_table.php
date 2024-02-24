<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->string('display_name', 100);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('business_location');
            $table->string('base_currency', 10);
            $table->unsignedInteger('fy_starts_month');
            $table->enum('fy_starts_from', ['Start', 'End'])->default('Start');
            $table->string('timezone', 50);
            $table->string('date_time_format');
            $table->time('business_starts_at');
            $table->time('business_end_at');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->string('company_logo')->nullable();
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
        Schema::dropIfExists('companies');
    }
}
