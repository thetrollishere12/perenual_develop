<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayoutHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payout_histories', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('store_id');
            $table->string('payout_method');
            $table->decimal('payout_amount', 65,2);
            $table->string('payout_destination');
            $table->string('transfer_id');
            $table->text('payout_order_number');
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
        Schema::dropIfExists('payout_histories');
    }
}
