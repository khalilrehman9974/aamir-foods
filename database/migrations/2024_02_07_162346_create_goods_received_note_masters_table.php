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
        Schema::create('goods_received_note_masters', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('purchase_order_no');
            $table->date('date');
            $table->string('supplier_name');
            $table->string('fare')->nullable();
            $table->string('supplier_bill_no')->nullable();
            $table->integer('transporter_id')->unsigned()->index()->nullable();
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->text('remarks')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');


            $table->foreign('transporter_id')
            ->references('id')->on('transporters')
            ->onDelete('cascade');

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
        Schema::dropIfExists('goods_received_note_masters');
    }
};
