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
        Schema::create('voucher_masters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->Increments('id');
            $table->date('date');
            $table->string('vr_type');
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->double('total_amount')->nullable();
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_masters');
    }
};