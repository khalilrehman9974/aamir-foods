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
        Schema::create('store_return_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_return_master_id')->unsigned()->index();
            $table->date('date', 150);
            $table->string('description');
            $table->integer('quantity');
            $table->TIMESTAMP('created_at');
            $table->TIMESTAMP('updated_at');
            $table->TIMESTAMP('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('store_return_master_id')
                ->references('id')->on('store_return_masters')
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
        Schema::dropIfExists('store_return_details');
    }
};
