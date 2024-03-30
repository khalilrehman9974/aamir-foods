<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('sale_masters');
    }

   /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('sale_masters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('dispatch_no', 20);
            $table->date('date');
            $table->integer('party_id');
            $table->integer('transporter_id');
            $table->string('bilty_no', 20);
            $table->string('deliverd_to', 20);
            $table->integer('salesman_id');
            $table->double('freight')->nullable();
            $table->double('scheme')->nullable();
            $table->double('commision')->nullable();
            $table->text('remarks')->nullable();
            $table->double('total_amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

        
        });
    }


};
