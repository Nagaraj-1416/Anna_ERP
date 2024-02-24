<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('expense_id')->index();
            $table->foreign('expense_id')->references('id')->on('expenses');

            $table->unsignedInteger('category_id')->index();
            $table->foreign('category_id')->references('id')->on('expense_categories');

            $table->unsignedInteger('expense_account')->index();
            $table->foreign('expense_account')->references('id')->on('accounts');

            $table->text('notes')->nullable();
            $table->double('amount')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_items');
    }
}
