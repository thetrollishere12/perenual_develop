<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            $table->string('unique_code')->unique();
            
            $table->string('main_image')->nullable();
            $table->string('title')->nullable();
            $table->longText('subtitle');
            $table->longText('description');
            $table->string('sub_image')->nullable();
            $table->integer('seen')->default(0);
            $table->integer('helpful')->default(0);
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
        Schema::dropIfExists('articles');
    }
}
