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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('account_code', 50);
            $table->string('account_name', 200);
            $table->string('account_type', 200);
            $table->unsignedBigInteger('parent_account_id')->nullable();
            $table->boolean('is_header');
            $table->boolean('is_active');
            $table->string('name', 250);
            $table->string('email', 200)->nullable();
            $table->string('mobile_number', 200);
            $table->string('whatsapp_number', 200)->nullable();
            $table->integer('sector_id');
            $table->integer('area_id');
            $table->string('mailing_address', 200)->nullable();
            $table->string('address', 200);
            $table->string('reference', 200)->nullable();
            $table->TEXT('remarks', 200)->nullable();
            $table->double('opening_balance', 200);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by', 20);
            $table->string('updated_by', 200);

            // $table->foreign('sector_id')
            // ->references('id')->on('sectors')
            // ->onDelete('cascade');

            // $table->foreign('area_id')
            // ->references('id')->on('areas')
            // ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
