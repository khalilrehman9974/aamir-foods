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
        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->double('debit')->nullable();
            $table->double('credit')->nullable();
            $table->string('transaction_type', 30);
            $table->TIMESTAMP('created_at');
            $table->TIMESTAMP('updated_at');
            $table->TIMESTAMP('deleted_at')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_ledgers');
    }
};
