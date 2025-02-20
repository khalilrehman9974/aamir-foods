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
        Schema::create('purchase_masters_log', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('grn_no');
            $table->integer('purchase_order_no');
            $table->date('date');
            $table->integer('party_id')->unsigned()->index();
            $table->integer('transporter_id')->unsigned()->index();
            $table->string('supplier_bill_no', 20);
            $table->string('unloaded_by');
            $table->double('carriage')->nullable();
            $table->double('gross_bill');
            $table->double('tax')->nullable();
            $table->double('net_amount');
            $table->double('total_quantity');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchase_masters_log');
    }
};
