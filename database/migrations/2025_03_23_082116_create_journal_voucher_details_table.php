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
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('voucher_master_id')->unsigned()->index();
            $table->integer('debit_account');
            $table->integer('credit_account');
            $table->string('description')->nullable();
            $table->double('debit');
            $table->double('credit');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('voucher_master_id')
            ->references('id')->on('journal_voucher_masters')
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
        Schema::dropIfExists('journal_voucher_details');
    }
};
