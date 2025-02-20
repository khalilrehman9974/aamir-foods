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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('purchase_master_id')->unsigned()->index();
            $table->integer('product_id')->unsigned()->index();
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->string('size')->nullable();
            $table->double('quantity');
            $table->double('price');
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('purchase_master_id')
            ->references('id')->on('purchase_masters')
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
        Schema::dropIfExists('purchase_details');
    }
};
