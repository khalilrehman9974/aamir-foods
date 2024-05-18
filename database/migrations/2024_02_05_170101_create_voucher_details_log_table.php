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
        Schema::create('voucher_details_log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->Increments('id');
            $table->integer('voucher_master_id')->unsigned()->index();
            $table->integer('account_id');
            $table->string('description');
            $table->double('debit');
            $table->double('credit');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voucher_details_log');
    }
};
