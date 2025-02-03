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
        Schema::create('delivered_to_parties', function (Blueprint $table) {
            $table->Increments('id');
            $table->foreignId('detail_account_id')->constrained('detail_accounts')->onDelete('cascade');
            $table->string('party_name', 200);
            $table->integer('saleMan_id')->unsigned()->index();
            $table->integer('commision')->default(0)->nullable();
            $table->integer('business_id');
            $table->integer('f_year_id');
            $table->string('mode');
            $table->string('status');
            $table->string('address', 250)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('cnic', 50)->nullable();
            $table->string('contact_no_1', 30)->nullable();
            $table->string('contact_no_2', 30)->nullable();
            $table->double('opening_balance')->nullable();
            $table->double('credit_limit')->nullable();
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
        Schema::dropIfExists('delivered_to_parties');
    }
};
