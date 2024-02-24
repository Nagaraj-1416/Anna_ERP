<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->mediumText('description')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->enum('is_deletable', ['Yes', 'No'])->default('Yes');
            $table->enum('is_mobile_enabled', ['Yes', 'No'])->default('No');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedInteger('account_id')->index()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_types');
    }
}
