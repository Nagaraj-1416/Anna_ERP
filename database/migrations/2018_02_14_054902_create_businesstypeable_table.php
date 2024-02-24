<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinesstypeableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesstypeable', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('business_type_id')->index();
            $table->unsignedInteger('businesstypeable_id')->index();
            $table->string('businesstypeable_type');
            $table->timestamps();

            // businesstypeable associated with a business type
            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesstypeable');
    }
}
