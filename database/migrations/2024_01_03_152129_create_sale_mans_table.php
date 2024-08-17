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
        Schema::create('sale_mans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 250);
            $table->string('email', 150)->nullable();
            $table->integer('country_id')->unsigned()->index();
            $table->integer('zone_id')->unsigned()->index();
            $table->integer('sector_id')->unsigned()->index();
            $table->integer('area_id')->unsigned()->index();
            $table->string('designation', 150);
            $table->string('mobile_no', 50);
            $table->string('whatsapp_no', 50)->nullable();
            $table->string('mailing_address', 250)->nullable();
            $table->text('address');
            $table->string('reference', 250)->nullable();
            $table->text('remarks', 250)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');

            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('cascade');

            $table->foreign('zone_id')
                ->references('id')->on('zones')
                ->onDelete('cascade');

            $table->foreign('sector_id')
                ->references('id')->on('sectors')
                ->onDelete('cascade');

            $table->foreign('area_id')
                ->references('id')->on('areas')
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
        Schema::dropIfExists('sale_mans');
    }
};
