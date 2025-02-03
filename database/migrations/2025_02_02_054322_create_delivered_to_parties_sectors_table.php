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
        Schema::create('delivered_to_parties_sectors', function (Blueprint $table) {
            $table->Increments('id');
            $table->integer('delivered_to_party_id')->unsigned()->index();
            $table->integer('sector_id')->unsigned()->index();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

            $table->foreign('delivered_to_party_id')
                ->references('id')->on('delivered_to_parties')
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
        Schema::dropIfExists('delivered_to_parties_sectors');
    }
};
