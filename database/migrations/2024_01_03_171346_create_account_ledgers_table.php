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
        Schema::create('account_ledgers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->integer('party_id');
            $table->string('description');
            $table->string('document_number');
            $table->integer('bags')->nullable();
            $table->integer('measurementType')->nullable();
            $table->integer('total_quantity');
            $table->integer('transporter_id')->unsigned()->index()->nullable();
            $table->string('bilty_no')->nullable();
            $table->integer('rate');
            $table->double('debit');
            $table->double('credit');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');

            $table->foreign('transporter_id')
                ->references('id')->on('transporters')
                ->onSoftDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_ledgers');
    }
};
