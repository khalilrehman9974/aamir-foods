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
        Schema::create('purchase_order_masters', function (Blueprint $table) {
            $table->Increments('id');
            $table->date('date');
            $table->foreignId('party_id')->constrained('detail_accounts')->onDelete('cascade');
            $table->string('contact_person');
            $table->string('status', 250);
            $table->text('remarks')->nullable();
            $table->double('gross_total');
            $table->double('tax_amount')->nullable();
            $table->double('shipping_amount')->nullable();
            $table->double('other_amount')->nullable();
            $table->double('total_amount');
            $table->integer('business_id');
            $table->integer('f_year_id')->unsigned()->index();
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
        Schema::dropIfExists('purchase_order_masters');
    }
};
