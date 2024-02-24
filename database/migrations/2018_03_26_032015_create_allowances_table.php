<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allowances', function (Blueprint $table) {
            $table->increments('id');
            $table->date('assigned_date');
            $table->float('amount')->default(0);

            $table->unsignedInteger('assigned_by')->index()->nullable();
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');

            $table->enum('is_active', ['Yes', 'No'])->default('Yes');

            $table->text('notes')->nullable();

            $table->string('allowanceable_type')->nullable();
            $table->unsignedInteger('allowanceable_id')->nullable();

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
        Schema::dropIfExists('allowances');
    }
}
