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
        Schema::create('bank_receipt_voucher_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('voucher_master_id')->unsigned()->index();
            $table->integer('account_id');
            $table->integer('bank_id');
            $table->string('description')->nullable();
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('voucher_master_id')
            ->references('id')->on('bank_receipt_vouchers')
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
        Schema::dropIfExists('bank_receipt_voucher_details');
    }
};
