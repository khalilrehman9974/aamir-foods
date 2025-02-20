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
        Schema::create('coa_detail_account_products', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('detail_account_id')->nullable();
            $table->integer('master_price_tag')->nullable();
            $table->integer('master_third_level')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('coa_inventory_detail_accounts')->onDelete('cascade');
            $table->string('price')->nullable();
            $table->string('discount')->default('0')->nullable();
            $table->string('scheme')->default('0')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
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
        Schema::dropIfExists('coa_detail_account_products');
    }
};
