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
        Schema::create('coa_inventory_detail_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_head')->nullable()->constrained('coa_inventory_main_heads')->onDelete('cascade');
            $table->foreignId('sub_head')->nullable()->constrained('coa_inventory_sub_heads')->onDelete('cascade');
            $table->foreignId('sub_sub_head')->nullable()->constrained('coa_inventory_sub_sub_heads')->onDelete('cascade');
            $table->integer('code');
            $table->string('name');
            $table->string('image')->nullable();
            $table->integer('price_tag_id');
            $table->integer('measurement_type_id');
            $table->integer('packing_type_id');
            $table->string('max_limit');
            $table->string('min_limit');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coa_inventory_detail_accounts');
    }
};
