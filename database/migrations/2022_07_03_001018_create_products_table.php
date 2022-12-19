<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id');
            $table->string('item_code')->nullable();
            $table->string('sku');
            $table->string('category');
            $table->string('style');
            $table->string('name');
            $table->string('default_image');
            $table->string('image');
            $table->string('image_360')->nullable();
            $table->string('currency');
            $table->decimal('price', 65,2)->nullable();
            $table->decimal('sale_price', 65,2)->nullable();
            $table->integer('shippingMethod');
            $table->text('tags')->nullable();
            // $table->string('brand')->nullable();
            // $table->string('item_link')->nullable();
            $table->integer('quantity')->nullable();
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
        Schema::dropIfExists('products');
    }
}
