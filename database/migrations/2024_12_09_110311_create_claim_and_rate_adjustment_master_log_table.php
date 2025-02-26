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
        Schema::create('claim_and_rate_adjustment_master_log', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('date');
            $table->foreignId('party_id')->constrained('detail_accounts')->onDelete('cascade');
            $table->integer('saleman');
            $table->integer('sector');
            $table->integer('business_id');
            $table->integer('f_year_id');
            $table->integer('area');
            $table->integer('delivered_to')->nullable();
            $table->string('driver_name');
            $table->integer('transporter');
            $table->string('bilty_no');
            $table->string('remarks');
            $table->double('total_boray')->nullable();
            $table->double('total_carton')->nullable();
            $table->double('gross_amount');
            $table->double('discount');
            $table->double('commission');
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
        Schema::dropIfExists('claim_and_rate_adjustment_master_log');
    }
};
