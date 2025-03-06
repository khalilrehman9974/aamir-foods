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
        Schema::create('store_issue_notes_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_issue_notes_id')->unsigned()->index();
            $table->integer('product_id');
            $table->string('packing_type');
            $table->string('measurement_type');
            $table->string('size')->nullable();
            $table->double('bags')->nullable();
            $table->double('avg_weight')->nullable();
            $table->double('total_qty');
            $table->string('remarks')->nullable();
            $table->TIMESTAMP('created_at');
            $table->TIMESTAMP('updated_at');
            $table->TIMESTAMP('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('store_issue_notes_id')
                ->references('id')->on('store_issue_notes')
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
        Schema::dropIfExists('store_issue_notes_detail');
    }
};
