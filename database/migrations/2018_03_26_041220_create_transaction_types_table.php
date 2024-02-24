<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();
            $table->string('name')->unique();
            $table->string('short_name')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->enum('mode', ['MoneyIn', 'MoneyOut']);
            $table->enum('is_default', ['Yes', 'No'])->default('No');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

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
        Schema::dropIfExists('transaction_types');
    }
}
