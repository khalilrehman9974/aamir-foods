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
            $table->integer('code');
            $table->string('name');
            $table->string('image')->nullable();
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
