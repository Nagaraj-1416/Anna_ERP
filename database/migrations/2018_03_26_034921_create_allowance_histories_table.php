<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowanceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowance_histories', function (Blueprint $table) {
            $table->increments('id');

            $table->date('date');
            $table->float('amount')->default(0);

            $table->text('notes')->nullable();

            $table->unsignedInteger('received_by')->index();
            $table->foreign('received_by')->references('id')->on('staff');

            $table->unsignedInteger('given_by')->index();
            $table->foreign('given_by')->references('id')->on('users');

            $table->unsignedInteger('allowance_id')->index();
            $table->foreign('allowance_id')->references('id')->on('allowances');

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
        Schema::dropIfExists('allowance_histories');
    }
}
