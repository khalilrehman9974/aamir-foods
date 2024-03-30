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
        Schema::create('store_issue_notes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->dateTime('issued_date');
            $table->string('issued_no', 20);
            $table->string('issued_to', 200);
            $table->integer('issued_quantity');
            $table->text('description');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('store_issue_notes');
    }
};
