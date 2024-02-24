<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('prefix')->nullable();
            $table->unsignedInteger('role_id')->index(); // refer to roles.id
            $table->enum('is_active', ['Yes', 'No'])->default('Yes');
            $table->enum('allowed_non_working_hrs', ['Yes', 'No'])->default('No');
            $table->enum('tfa', ['Yes', 'No'])->default('Yes');
            $table->datetime('tfa_expiry')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            // User associated with a role
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
