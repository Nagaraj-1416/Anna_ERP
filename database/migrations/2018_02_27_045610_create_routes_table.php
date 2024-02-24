<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('name')->nullable();
            $table->text('notes')->nullable();
            $table->string('start_point')->nullable();
            $table->string('end_point')->nullable();
            $table->longText('way_points')->nullable();

            $table->double('cl_amount')->default(0)->nullable();
            $table->double('cl_notify_rate')->default(0)->nullable();

            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

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
        Schema::dropIfExists('routes');
    }
}
