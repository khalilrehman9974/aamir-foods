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
        Schema::dropIfExists('purchase_return_masters');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('purchase_return_masters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('grn_no', 20)->nullable();
            $table->date('date');
            $table->integer('party_id')->unsigned()->index();
            $table->integer('transporter_id')->unsigned()->index();
            $table->string('bill_no', 20);
            $table->double('fare')->nullable();
            $table->double('carriage_inward')->nullable();
            $table->double('total_amount');
            $table->text('remarks')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('party_id')
            ->references('id')->on('chart_of_accounts')
            ->onDelete('cascade');

            $table->foreign('transporter_id')
            ->references('id')->on('transporters')
            ->onDelete('cascade');
        });
    }
};
