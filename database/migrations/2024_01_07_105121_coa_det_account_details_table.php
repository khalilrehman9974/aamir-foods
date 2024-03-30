<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coa_det_account_details', function (Blueprint $table) {
            $table->id();
            $table->integer('det_account_code')->nullable();
            $table->string('address', 250)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('cnic', 50)->nullable();
            $table->string('contact_no_1', 30)->nullable();
            $table->string('contact_no_2', 30)->nullable();
            $table->double('opening_balance')->nullable();
            $table->double('credit_limit')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
        });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coa_det_account_details');
    }
};
