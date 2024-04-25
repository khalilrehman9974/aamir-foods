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
        Schema::create('purchase_masters', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('grn_no');
            $table->string('type');
            $table->date('date');
            $table->integer('party_id');
            $table->string('bill_no');
            $table->string('fare')->nullable();
            $table->integer('transporter_id')->nullable();
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
            $table->double('carriage_inward')->nullable();
            $table->double('total_amount')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchase_masters');
    }
};
