<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_elements', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->longText('description')->nullable();
            $table->integer('seen')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_elements');
    }
}
