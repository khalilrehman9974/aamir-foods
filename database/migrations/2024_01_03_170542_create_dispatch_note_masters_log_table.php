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
            $table->string('sale_order_number', 250);
            $table->date('date', 150);
            $table->integer('party_id');
            $table->integer('saleman');
            $table->integer('sector');
            $table->integer('area');
            $table->integer('delivered_to');
            $table->string('vehicle_no', 250);
            $table->string('bility_no', 250);
            $table->string('driver_name', 250);
            $table->double('carriage');
            $table->double('total_boray')->nullable();
            $table->double('total_carton')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
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
