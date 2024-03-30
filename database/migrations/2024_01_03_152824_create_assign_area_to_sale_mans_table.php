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
        Schema::create('assign_area_to_sale_mans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('area_id')->unsigned()->index();
            $table->integer('sale_mans_id')->unsigned()->index();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('area_id')
            ->references('id')->on('areas')
            ->onDelete('cascade');

            $table->foreign('sale_mans_id')
            ->references('id')->on('sale_mans')
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
        Schema::dropIfExists('assign_area_to_sale_mans');
    }
};
