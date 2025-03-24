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
        Schema::create('cash_receipt_voucher_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('voucher_master_id')->unsigned()->index();
            $table->integer('account_id');
            $table->integer('cash_account_id');
            $table->string('description')->nullable();
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('voucher_master_id')
            ->references('id')->on('cash_receipt_vouchers')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_receipt_voucher_details');
    }
};
