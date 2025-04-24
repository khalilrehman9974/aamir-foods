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
        Schema::create('coa_inventory_sub_sub_head_price_tag', function (Blueprint $table) {
            $table->Increments('id');
            $table->foreignId('sub_sub_head_id')->constrained('coa_sub_sub_heads')->onDelete('cascade');
            $table->integer('priceTag')->unsigned()->index();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('priceTag')
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
        Schema::dropIfExists('coa_inventory_sub_sub_head_price_tag');
    }
};
