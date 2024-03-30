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
        Schema::create('chart_of_accounts_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_code');
            $table->string('account_name', 200);
            $table->string('account_type', 200);
            $table->integer('parent_account_id')->nullable();
            $table->boolean('is_header');
            $table->boolean('is_active');
            $table->string('name', 250);
            $table->string('email', 200)->nullable();
            $table->string('mobile_number', 200);
            $table->string('whatsapp_number', 200)->nullable();
            $table->string('mailing_address', 200)->nullable();
            $table->string('sector', 200);
            $table->string('area', 200);
            $table->string('address', 200);
            $table->string('reference', 200)->nullable();
            $table->TEXT('remarks', 200)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->timestamp('deleted_at')->nullable();
            $table->string('created_by', 200);
            $table->string('updated_by', 200);

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_of_accounts_log');
    }
};
