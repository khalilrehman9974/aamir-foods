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
            $table->date('date')->index();
            $table->integer('invoice_id');
            $table->integer('party_id')->index(); 
            $table->string('description');
            $table->string('document_number');
            $table->integer('bags')->nullable();
            $table->integer('measurementType')->nullable();
            $table->integer('total_quantity');
            $table->unsignedInteger('transporter_id')->nullable()->index();
            $table->string('bilty_no')->nullable();
            $table->integer('rate');
            $table->double('debit');
            $table->double('credit');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();



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
