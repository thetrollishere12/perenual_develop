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
        Schema::create('propagation_methods', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->longText('description')->nullable();

            $table->longText('method')->default('[]')->nullable();

            $table->text('image')->nullable();

            $table->text('default_image')->nullable();

            $table->text('folder')->nullable();

            $table->longText('attributes')->nullable();

            $table->text('tags')->nullable();

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
        Schema::dropIfExists('propagation_methods');
    }
};
