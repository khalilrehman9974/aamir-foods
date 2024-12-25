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
        Schema::create('claim_and_rate_adjustment_detail', function (Blueprint $table) {
            $table->Increments('id');
            // $table->foreignId('master_id')->constrained('claim_and_rate_adjustment_master')->onDelete('cascade');
            $table->integer('master_id')->unsigned()->index();
            $table->foreignId('product_id')->constrained('coa_inventory_detail_accounts')->onDelete('cascade');
            // $table->integer('product_id');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->double('quantity');
            $table->double('dzn');
            $table->double('total_dzn');
            $table->double('rate');
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('master_id')
            ->references('id')->on('claim_and_rate_adjustment_master')
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
        Schema::dropIfExists('claim_and_rate_adjustment_detail');
    }
};
