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
        Schema::create('goods_received_note_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('grn_master_id')->unsigned()->index();
            $table->integer('product_id');
            $table->double('quantity');
            $table->string('remarks');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('grn_master_id')
            ->references('id')->on('goods_received_note_masters')
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
        Schema::dropIfExists('goods_received_note_details');
    }
};
