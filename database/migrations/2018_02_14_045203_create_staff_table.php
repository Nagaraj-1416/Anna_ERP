<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 50);
            $table->string('salutation', 100)->nullable();
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('full_name', 200);
            $table->string('short_name', 100);
            $table->enum('gender', ['Male', 'Female']);
            $table->date('dob')->nullable();
            $table->string('email', 100);
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20);
            $table->date('joined_date')->nullable();
            $table->string('designation')->nullable();
            $table->date('resigned_date')->nullable();

            $table->string('bank_name', 100)->nullable();
            $table->string('branch', 100)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->string('account_no', 20)->nullable();

            $table->string('epf_no', 10)->nullable();
            $table->string('etf_no', 10)->nullable();

            $table->enum('pay_rate', ['Monthly', 'Weekly', 'Hourly']);

            $table->text('notes')->nullable();
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->unsignedInteger('user_id')->index()->nullable();
            $table->string('profile_image')->nullable();
            $table->enum('is_sales_rep', ['Yes', 'No'])->default('No');
            $table->timestamps();
            $table->softDeletes();

            // staff associated with a user
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
}
