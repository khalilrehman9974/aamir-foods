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
        Schema::create('sale_order_masters', function (Blueprint $table) {
            $table->Increments('id');
            $table->date('date');
            $table->integer('party_id');
            $table->string('bilty_no');
            $table->string('deliverd_to');
            $table->integer('saleman_id')->unsigned()->index();
            $table->integer('transporter_id');
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->text('remarks')->nullable();
            $table->double('total_amount')->nullable();
            $table->double('freight')->nullable();
            $table->double('scheme')->nullable();
            $table->double('commission')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');


            $table->foreign('saleman_id')
            ->references('id')->on('sale_mans')
            ->onDelete('cascade');

            $table->foreign('f_year_id')
            ->references('id')->on('financial_years')
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
        Schema::dropIfExists('sale_order_masters');
    }
};
