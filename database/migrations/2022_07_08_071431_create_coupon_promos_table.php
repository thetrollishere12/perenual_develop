<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_promos', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code');
            $table->string('coupon_type');
            $table->bigInteger('coupon_to_user')->nullable();
            $table->decimal('amount_off', 65,2)->nullable();
            $table->decimal('percent_off', 65,2)->nullable();
            $table->string('shipping_off')->nullable();
            $table->decimal('price_minimum', 65,2)->nullable();
            $table->string('required_type')->nullable();
            $table->string('required_type_name')->nullable();
            $table->string('max_redemptions');
            $table->datetime('redeemed_by')->nullable();
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
        Schema::dropIfExists('coupon_promos');
    }
}
