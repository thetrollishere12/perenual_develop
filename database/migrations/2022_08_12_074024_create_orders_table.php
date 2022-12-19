<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cus_order_id');
            $table->bigInteger('store_id');
            $table->string('number')->unique();
            $table->string('currency');
            $table->decimal('subtotal', 65,2);
            $table->decimal('shipping', 65,2);
            $table->decimal('discount', 65,2);
            $table->decimal('tax', 65,2);
            $table->decimal('total', 65,2);
            // $table->decimal('payout_amount', 65,2);
            // $table->string('payout_method');
            // $table->string('transfer_id');
            $table->string('type');
            $table->string('status')->nullable();
            $table->date('status_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
