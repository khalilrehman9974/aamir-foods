<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('cash_payment_voucher_masters');
    }
   public function down()
    {

        Schema::create('cash_payment_voucher_masters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->Increments('id');
            $table->date('date');
            $table->integer('f_year_id')->unsigned()->index();
            $table->double('total_amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');


            $table->foreign('f_year_id')
                ->references('id')->on('financial_years')
                ->onDelete('cascade');
        });
    }

};
