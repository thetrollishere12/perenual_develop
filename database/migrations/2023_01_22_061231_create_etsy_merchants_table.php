<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etsy_merchants', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->nullable();
            $table->string('name')->nullable();
            $table->string('shop_id')->nullable();
            $table->decimal('rating', 65,2)->nullable();
            $table->integer('review')->nullable();
            $table->integer('total_products')->nullable();
            $table->integer('sales')->nullable();
            $table->text('members')->nullable();
            $table->text('link')->nullable();
            $table->text('social_media')->nullable();
            $table->text('website')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->boolean('checked')->default(0);
            $table->boolean('invalid')->default(0);
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
        Schema::dropIfExists('etsy_merchants');
    }
};
