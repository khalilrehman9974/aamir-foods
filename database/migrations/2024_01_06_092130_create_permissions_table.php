<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('menu_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->tinyInteger('menu_access')->unsigned();
            $table->tinyInteger('select_access')->unsigned();
            $table->tinyInteger('insert_access')->unsigned();
            $table->tinyInteger('edit_access')->unsigned();
            $table->tinyInteger('delete_access')->unsigned();
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
        Schema::dropIfExists('permissions');
    }
};
