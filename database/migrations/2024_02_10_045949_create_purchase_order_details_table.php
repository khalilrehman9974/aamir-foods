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
            $table->string('size')->nullable();
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->double('quantity');
            $table->double('price');
            $table->double('amount');
            $table->string('detail_remarks')->nullable();
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
