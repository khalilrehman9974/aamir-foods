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
        Schema::dropIfExists('dispatch_note_masters');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('dispatch_note_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_no', 50);
            $table->date('date', 150);
            $table->integer('party_id');
            $table->string('whatsapp_no', 50);
            $table->string('mailing_address', 250);
            $table->text('address');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at');
            $table->string('created_by');
            $table->string('updated_by');
        });
    }
};
