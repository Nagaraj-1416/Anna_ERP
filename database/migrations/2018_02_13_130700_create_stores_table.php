<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
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
        Schema::dropIfExists('stores');
    }
}
