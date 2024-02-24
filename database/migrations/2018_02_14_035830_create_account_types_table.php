<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code', 50)->unique();
            $table->string('name')->unique();
            $table->string('short_name')->unique();
            $table->text('notes')->nullable();
            $table->enum('is_default', ['Yes', 'No'])->default('No');
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

            $table->unsignedInteger('account_category_id')->index();
            $table->foreign('account_category_id')->references('id')->on('account_categories')->onDelete('cascade');


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
        Schema::dropIfExists('account_types');
    }
}
