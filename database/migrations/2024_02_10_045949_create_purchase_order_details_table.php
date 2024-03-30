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
        Schema::create('purchase_order_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('purchase_order_master_id')->unsigned()->index();
            $table->integer('product_id');
            $table->double('total_quantity');
            $table->date('Schedule_date');
            $table->double('Schedule_quantity');
            $table->date('Delivery_date');
            $table->double('Delivery_quantity');
            $table->double('price');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('purchase_order_master_id')
            ->references('id')->on('purchase_order_masters')
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
        Schema::dropIfExists('purchase_order_details');
    }
};
