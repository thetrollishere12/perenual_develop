<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_balances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('store_id');
            $table->text('ref_number')->nullable();
            $table->string('type');
            $table->string('currency');
            $table->decimal('debit', 65,2)->default(0);
            $table->decimal('credit', 65,2)->default(0);
            $table->decimal('balance', 65,2)->default(0);
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
        Schema::dropIfExists('bank_balances');
    }
}
