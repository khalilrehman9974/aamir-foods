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
        Schema::create('detail_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('main_head', 30);
            $table->string('control_head', 30);
            $table->string('sub_head', 30);
            $table->string('sub_sub_head', 30);
            $table->integer('account_code')->unsigned();
            $table->string('account_name', 200);
            $table->integer('saleMan_id')->unsigned()->index();
            $table->integer('business_id');
            $table->integer('f_year_id');
            $table->string('commision')->default(0)->nullable();
            $table->string('mode');
            $table->string('status');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

            $table->foreign('saleMan_id')
            ->references('id')->on('sale_mans')
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
        Schema::dropIfExists('detail_account');
    }
};
