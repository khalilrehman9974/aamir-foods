<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('cash_payment_voucher_details_log');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('cash_payment_voucher_details_log', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('account_code')->unsigned()->index();
            $table->text('description')->nullable();
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
        });
    }
};
