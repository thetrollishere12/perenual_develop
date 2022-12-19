<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingDomesticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_domestics', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('name')->nullable();
            $table->string('origin');
            // $table->string('postal');
            $table->string('processing');
            $table->decimal('cost',65,2)->default(0);
            $table->decimal('additional_cost',65,2)->default(0);
            $table->boolean('free_shipping')->default(0);
            $table->integer('delivery_from');
            $table->integer('delivery_to');
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
        Schema::dropIfExists('shipping_domestics');
    }
}
