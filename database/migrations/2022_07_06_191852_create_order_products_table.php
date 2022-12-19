<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('sku');
            $table->string('currency_rate');
            $table->decimal('from_price',65,2);
            $table->decimal('to_price',65,2);
            $table->integer('quantity');
            $table->decimal('tax',65,2);

            $table->decimal('store_earning',65,2);
            $table->decimal('marketplace_fee',65,2);

            $table->text('shipping');

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
        Schema::dropIfExists('order_products');
    }
}
