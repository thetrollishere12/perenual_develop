<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutExternalAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_external_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('default_method')->nullable();
            $table->string('account_id');
            $table->string('bank_id');
            $table->string('bank_name');
            $table->string('bank_last4');
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
        Schema::dropIfExists('payout_external_accounts');
    }
}
