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
        Schema::create('dispatch_note_masters_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_no', 50);
            $table->date('date', 150);
            $table->integer('sale_man_id');
            $table->integer('party_id');
            $table->integer('transporter_id');
            $table->string('bilty_no', 50);
            $table->string('contact_no');
            $table->double('fare')->nullable();
            $table->double('total_balance');
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
    public function down()
    {
        Schema::dropIfExists('dispatch_note_masters_log');
    }
};
