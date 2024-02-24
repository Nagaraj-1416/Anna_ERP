<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupplierCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_credits', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50);
            $table->date('date');
            $table->float('amount')->default(0);
            $table->text('notes')->nullable();

            $table->enum('status', ['Open', 'Closed', 'Canceled'])->default('Open');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('supplier_id')->index();
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedInteger('business_type_id')->index();
            $table->foreign('business_type_id')->references('id')->on('business_types');

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
        Schema::dropIfExists('supplier_credits');
    }
}
