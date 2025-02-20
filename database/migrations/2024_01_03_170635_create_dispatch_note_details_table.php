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
        Schema::create('dispatch_note_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispatch_note_master_id')->unsigned()->index();
            $table->integer('product_id');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->double('quantity');
            $table->double('dzn');
            $table->double('total_dzn');
            $table->text('remarks')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('dispatch_note_master_id')
                ->references('id')->on('dispatch_note_masters')
                ->onSoftDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispatch_note_details');
    }
};
