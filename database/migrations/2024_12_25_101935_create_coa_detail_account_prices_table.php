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
        Schema::create('coa_detail_account_prices', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('coa_detail_account_code')->unsigned();
            $table->foreignId('inventory_third_level')->constrained('coa_inventory_sub_sub_heads')->onDelete('cascade');
            $table->integer('price_tag_id')->unsigned()->index();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('price_tag_id')
                ->references('id')->on('price_tags')
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
        Schema::dropIfExists('coa_detail_account_prices');
    }
};
