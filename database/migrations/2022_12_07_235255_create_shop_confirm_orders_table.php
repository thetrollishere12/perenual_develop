<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopConfirmOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_confirm_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cus_order_id');
            $table->bigInteger('store_id');
            $table->string('number');
            $table->string('currency');
            $table->decimal('subtotal', 65,2);
            $table->decimal('shipping', 65,2);
            $table->decimal('discount', 65,2);
            $table->decimal('tax', 65,2);
            $table->decimal('total', 65,2);
            $table->text('fee_breakdown');
            $table->decimal('marketplace_fee', 65,2);
            $table->decimal('currency_rate', 65,2);
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
        Schema::dropIfExists('shop_confirm_orders');
    }
}
