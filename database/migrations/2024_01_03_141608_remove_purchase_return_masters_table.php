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
        Schema::dropIfExists('purchase_return_masters');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('purchase_return_masters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('grn_no', 20);
            $table->date('date');
            $table->bigInteger('party_id');
            $table->string('bill_no', 20);
            $table->double('amount');
            $table->double('quantity');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
};
