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
        Schema::create('disease_images', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name');
            $table->text('origin_url');
            $table->string('folder');
            $table->string('name');
            $table->integer('license')->nullable();
            $table->string('license_name')->nullable();
            $table->text('license_url')->nullable();
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
        Schema::dropIfExists('disease_images');
    }
};
