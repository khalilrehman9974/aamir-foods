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
        Schema::create('defective_items_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('master_id')->unsigned()->index();
            $table->integer('from_department')->unsigned()->index();
            $table->foreignId('product_id')->constrained('coa_inventory_detail_accounts')->onDelete('cascade');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->string('size')->nullable();
            $table->string('bags')->nullable();
            $table->string('avg_weight')->nullable();
            $table->double('total_quantity');
            $table->string('remarks')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('master_id')
            ->references('id')->on('defective_items')
            ->onDelete('cascade');

            $table->foreign('from_department')
            ->references('id')->on('departments')
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
        Schema::dropIfExists('defective_items_details');
    }
};
