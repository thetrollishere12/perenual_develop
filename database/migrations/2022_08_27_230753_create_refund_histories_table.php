<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_histories', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->string('refund_id');
            $table->string('type');
            $table->decimal('reversed_amount', 65,2);
            $table->decimal('customer_reversed_amount', 65,2);
            $table->string('order_product_sku')->nullable();
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
        Schema::dropIfExists('refund_histories');
    }
}
