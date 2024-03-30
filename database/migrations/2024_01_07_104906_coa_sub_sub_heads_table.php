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
        Schema::create('coa_sub_sub_heads', function (Blueprint $table) {
            $table->id();
            $table->string('main_head', 30);
            $table->string('control_head', 30);
            $table->string('sub_head', 50);
            $table->string('account_code', 50);
            $table->string('account_name', 200);
            $table->dateTime('deleted_at')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('coa_sub_sub_heads');
    }
};
