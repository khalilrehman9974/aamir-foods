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
        Schema::create('dispatch_note_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_no', 50);
            $table->date('date', 150);
            $table->integer('sale_man_id')->unsigned()->index();
            $table->integer('party_id');
            $table->integer('transporter_id')->unsigned()->index();
            $table->string('bilty_no', 50);
            $table->string('contact_no');
            $table->double('fare')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            

            $table->foreign('transporter_id')
            ->references('id')->on('transporters')
            ->onDelete('cascade');

            $table->foreign('sale_man_id')
            ->references('id')->on('sale_mans')
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
        Schema::dropIfExists('dispatch_note_masters');
    }
};
