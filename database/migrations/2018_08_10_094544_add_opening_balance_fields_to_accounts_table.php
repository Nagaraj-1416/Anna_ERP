<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOpeningBalanceFieldsToAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->double('opening_balance')->nullable();
            $table->date('opening_balance_at')->nullable();
            $table->enum('opening_balance_type', ['Debit', 'Credit'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn(['opening_balance']);
            $table->dropColumn(['opening_balance_at']);
            $table->dropColumn(['opening_balance_type']);
        });
    }
}
