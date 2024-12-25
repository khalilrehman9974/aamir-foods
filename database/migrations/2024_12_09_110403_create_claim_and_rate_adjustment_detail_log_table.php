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
        Schema::create('claim_and_rate_adjustment_detail_log', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('master_id')->unsigned()->index();
            $table->foreignId('product_id')->constrained('coa_inventory_detail_accounts')->onDelete('cascade');
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claim_and_rate_adjustment_detail_log');
    }
};
