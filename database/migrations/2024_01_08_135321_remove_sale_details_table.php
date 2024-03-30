<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sale_details');
    }
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('sale_master_id');
            $table->integer('product_id');
            $table->double('quantity');
            $table->double('unit');
            $table->double('total_unit');
            $table->double('rate');
            $table->double('amount');
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

};
