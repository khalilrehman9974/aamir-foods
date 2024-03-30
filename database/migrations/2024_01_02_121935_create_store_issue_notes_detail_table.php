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
            $table->increments('id')->unsigned();
            $table->integer('store_issue_notes_id')->unsigned()->index();
            $table->dateTime('date');
            $table->string('description');
            $table->integer('quantity');
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
