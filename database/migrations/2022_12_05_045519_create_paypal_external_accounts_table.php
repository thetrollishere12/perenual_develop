<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalExternalAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('paypal_external_accounts', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('user_id');
        //     $table->string('default_method')->nullable();
        //     $table->string('payment_method');
        //     $table->string('paypal_email');
        //     $table->string('paypal_payer_id');
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypal_external_accounts');
    }
}
