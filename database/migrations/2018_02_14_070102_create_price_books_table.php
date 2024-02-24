<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name');
            $table->enum('category', ['Production To Store', 'Store To Shop', 'Shop Selling Price', 'Van Selling Price']);
            $table->enum('type', ['Selling Price', 'Buying Price']);
            $table->text('notes')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');
            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedInteger('related_to_id')->index();
            $table->string('related_to_type');
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
        Schema::dropIfExists('price_books');
    }
}
