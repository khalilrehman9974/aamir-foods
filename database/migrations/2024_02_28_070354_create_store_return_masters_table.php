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
        Schema::create('store_return_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('receiver_name', 200);
            $table->integer('business_id');
            $table->integer('f_year_id');
            $table->integer('from_department');
            $table->integer('to_department');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_return_masters');
    }
};
