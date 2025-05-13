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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('sale_master_id')->unsigned()->index();
            $table->string('product_id');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->double('soQuantity');
            $table->double('dispQuantity');
            $table->double('quantity');
            $table->double('dzns');
            $table->double('total_dzns');
            $table->double('rate');
            $table->double('discount');
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('sale_master_id')
            ->references('id')->on('sale_masters')
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
        Schema::dropIfExists('sale_details');
    }
};
