<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_reviews', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');
            $table->enum('status', ['Drafted', 'Approved'])->default('Drafted');
            $table->text('notes')->nullable();

            $table->unsignedInteger('prepared_by')->index()->nullable();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->dateTime('prepared_on')->nullable();

            $table->unsignedInteger('approved_by')->index()->nullable();
            $table->foreign('approved_by')->references('id')->on('users');

            $table->dateTime('approved_on')->nullable();

            $table->unsignedInteger('store_id')->index();
            $table->foreign('store_id')->references('id')->on('stores');

            $table->unsignedInteger('staff_id')->index();
            $table->foreign('staff_id')->references('id')->on('staff');

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
        Schema::dropIfExists('stock_reviews');
    }
}
