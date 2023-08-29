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
        Schema::create('my_plants', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('seller_id')->nullable();
            $table->string('plant_id');
            $table->string('common_name')->nullable();
            $table->longText('species')->nullable();
            $table->string('season')->nullable();
            $table->string('name');
            $table->string('default_image');
            $table->string('image');
            $table->longText('description')->nullable();
            $table->integer('seen')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('update_request')->default(0);
            $table->longText('attributes')->nullable();
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
        Schema::dropIfExists('my_plants');
    }
};
