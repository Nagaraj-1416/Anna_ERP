<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('estimate_no', 50);
            $table->date('estimate_date');
            $table->date('expiry_date')->nullable();

            $table->text('terms')->nullable();
            $table->text('notes')->nullable();

            $table->double('sub_total')->default(0);
            $table->double('discount')->default(0);
            $table->double('discount_rate')->default(0);
            $table->enum('discount_type', ['Amount', 'Percentage']);
            $table->double('adjustment')->default(0);
            $table->double('total')->default(0);

            $table->enum('status', ['Draft', 'Sent', 'Accepted', 'Declined', 'Ordered'])->default('Draft');
            $table->enum('order_status', ['Pending', 'Ordered'])->default('Pending');

            $table->unsignedInteger('rep_id')->index();
            $table->foreign('rep_id')->references('id')->on('reps');

            $table->unsignedInteger('prepared_by')->index();
            $table->foreign('prepared_by')->references('id')->on('users');

            $table->unsignedInteger('customer_id')->index();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedInteger('business_type_id')->index();
            $table->foreign('business_type_id')->references('id')->on('business_types');

            $table->unsignedInteger('company_id')->index();
            $table->foreign('company_id')->references('id')->on('companies');

            $table->string('converted_type')->nullable();
            $table->string('converted_id')->nullable();

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
        Schema::dropIfExists('estimates');
    }
}
