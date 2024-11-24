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
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->string('saleman', 250);
            $table->string('belt', 250);
            $table->string('area', 250);
            $table->string('delivered_to', 250);
            $table->string('status', 250);
            $table->double('total_boray')->nullable();
            $table->double('total_carton')->nullable();
            $table->text('remarks')->nullable();
            $table->double('total_amount')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');


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
