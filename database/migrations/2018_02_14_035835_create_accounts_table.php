<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();
            $table->string('name')->unique();
            $table->string('short_name')->unique();
            $table->text('notes')->nullable();
            $table->enum('is_default', ['Yes', 'No'])->default('No');

            /** closing account balance from the previous accounting period, carried over as the
             * opening account balance for a new accounting period. */
            $table->enum('closing_bl_carried', ['Yes', 'No'])->default('No');

            /** the date of very first transaction made */
            $table->date('first_tx_date')->nullable();

            /** the date of very latest transaction made */
            $table->date('latest_tx_date')->nullable();

            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

            $table->unsignedInteger('accountable_id')->nullable();
            $table->string('accountable_type')->nullable();

            $table->unsignedInteger('parent_account_id')->index()->nullable();
            $table->foreign('parent_account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->unsignedInteger('account_type_id')->index();
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');

            $table->unsignedInteger('account_category_id')->index();
            $table->foreign('account_category_id')->references('id')->on('account_categories')->onDelete('cascade');

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
        Schema::dropIfExists('accounts');
    }
}
