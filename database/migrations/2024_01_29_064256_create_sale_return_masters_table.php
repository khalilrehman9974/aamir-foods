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
            $table->Increments('id');
            $table->string('sale_return_number');
            $table->date('date');
            $table->string('party_id');
            $table->string('saleman');
            $table->string('sector');
            $table->string('area');
            $table->string('deliverd_to');
            $table->string('driver_name');
            $table->string('bilty_no');
            $table->string('transporter');
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->text('remarks')->nullable();
            $table->double('gross_amount');
            $table->double('boray_amount')->nullable();
            $table->double('carton_amount')->nullable();
            $table->double('scheme')->nullable();
            $table->double('commission')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

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
        Schema::dropIfExists('sale_return_masters');
    }
};
