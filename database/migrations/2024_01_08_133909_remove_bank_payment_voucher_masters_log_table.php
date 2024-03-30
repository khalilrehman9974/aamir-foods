<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bank_payment_voucher_masters_log');
    }


   /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('bank_payment_voucher_masters_log', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

};
