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
        Schema::create('transporters_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 250);
            $table->string('city', 150);
            $table->string('address', 250);
            $table->string('contact_number', 50);
            $table->string('contact_person', 50)->nullable();
            $table->string('remarks', 250);
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
        Schema::dropIfExists('transporters_log');
    }
};
