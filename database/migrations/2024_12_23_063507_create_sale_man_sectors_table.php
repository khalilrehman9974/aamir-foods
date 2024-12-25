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
        Schema::create('sale_man_sectors', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('master_id')->unsigned()->index();
            $table->integer('sector_id')->unsigned()->index();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('master_id')
            ->references('id')->on('sale_mans')
            ->onDelete('cascade');

            $table->foreign('sector_id')
                ->references('id')->on('sectors')
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
        Schema::dropIfExists('sale_man_sectors');
    }
};
