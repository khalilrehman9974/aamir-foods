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
            $table->integer('master_id')->unsigned()->index();
            $table->foreignId('product_id')->constrained('coa_inventory_detail_accounts')->onDelete('cascade');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->string('size')->nullable();
            $table->double('po_quantity');
            $table->double('received_qty');
            $table->double('balance');
            $table->string('detail_remarks')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('master_id')
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
