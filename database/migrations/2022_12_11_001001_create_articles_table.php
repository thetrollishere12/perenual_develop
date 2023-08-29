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
            $table->integer('publish_id')->nullable();

            $table->integer('parent_id')->nullable();

            $table->text('main_image')->nullable();
            $table->text('image_path')->nullable();

            $table->string('title')->nullable();
            $table->longText('description')->nullable();

            $table->longText('tags');
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
