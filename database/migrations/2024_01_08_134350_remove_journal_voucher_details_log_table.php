<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('journal_voucher_details');
    }
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->Increments('id');
            $table->string('code', 50);
            $table->integer('account_code')->unsigned()->index();
            $table->string('description', 250)->nullable();
            $table->double('amount');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by');
            $table->string('updated_by');
        });
    }
};
