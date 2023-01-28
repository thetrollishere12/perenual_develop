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
        Schema::create('article_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('article_code')->nullable();
            // $table->string('common_name')->nullable();
            $table->text('image')->nullable();
            $table->string('question');
            $table->longText('answer');
            $table->longText('tags')->nullable();
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
        Schema::dropIfExists('article_faqs');
    }
};
