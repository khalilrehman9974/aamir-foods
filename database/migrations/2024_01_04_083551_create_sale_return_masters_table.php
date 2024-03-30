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
        Schema::create('sale_return_masters', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('dispatch_no', 20);
            $table->date('date');
            $table->integer('party_id')->unsigned()->index();
            $table->integer('transporter_id')->unsigned()->index();
            $table->string('bilty_no', 20);
            $table->string('deliverd_to', 20);
            $table->integer('salesman_id')->unsigned()->index();
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

            $table->foreign('party_id')
            ->references('id')->on('chart_of_accounts')
            ->onDelete('cascade');

            $table->foreign('transporter_id')
            ->references('id')->on('transporters')
            ->onDelete('cascade');

            $table->foreign('salesman_id')
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
        Schema::dropIfExists('sale_return_masters');
    }
};
