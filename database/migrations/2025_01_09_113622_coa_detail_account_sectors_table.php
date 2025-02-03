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
        Schema::create('coa_detail_account_sectors', function (Blueprint $table) {
            $table->Increments('id');
            $table->foreignId('master_account_id')->constrained('detail_accounts')->onDelete('cascade');
            $table->integer('sector_id')->unsigned()->index();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();

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
        Schema::dropIfExists('coa_detail_account_sectors');
    }
};
