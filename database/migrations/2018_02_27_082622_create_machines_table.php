<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name', 100);
            $table->double('purchase_price')->default(0);
            $table->double('purchase_date')->default(0);
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer_country')->nullable();
            $table->string('manufacturer_year')->nullable();
            $table->date('warranty_date')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->unsignedInteger('company_id')->index();
            $table->unsignedInteger('supplier_id')->index();
            $table->longText('specifications')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // The machines that  belong to the company
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('machines');
    }
}
