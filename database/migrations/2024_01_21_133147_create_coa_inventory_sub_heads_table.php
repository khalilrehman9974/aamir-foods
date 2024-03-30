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
        Schema::create('coa_inventory_sub_heads', function (Blueprint $table) {
            $table->id();
//            $table->integer('main_head')->index();
            $table->integer('code')->index();
            $table->string('name');
            $table->foreignId('main_head')->nullable()->constrained('coa_inventory_main_heads')->onDelete('cascade');
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
        Schema::dropIfExists('coa_inventory_sub_heads');
    }
};
