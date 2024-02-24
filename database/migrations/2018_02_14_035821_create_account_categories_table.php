<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();
            $table->string('name')->unique();
            $table->enum('balance_type', ['Debit', 'Credit']);
            $table->text('notes')->nullable();

            /** closing account balance from the previous accounting period, carried over as the
             * opening account balance for a new accounting period. */
            $table->enum('closing_bl_carried', ['Yes', 'No'])->default('No');

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
        Schema::dropIfExists('account_categories');
    }
}
