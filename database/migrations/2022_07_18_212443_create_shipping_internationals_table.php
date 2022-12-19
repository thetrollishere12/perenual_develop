<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingInternationalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_internationals', function (Blueprint $table) {
            $table->id();
            $table->integer('shipping_id');
            $table->string('origin');
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
        Schema::dropIfExists('shipping_internationals');
    }
}
