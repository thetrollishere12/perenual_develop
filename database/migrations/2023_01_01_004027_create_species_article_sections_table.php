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
        Schema::create('species_article_sections', function (Blueprint $table) {
            $table->id();

            $table->string('common_name')->nullable();
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
        Schema::dropIfExists('species_article_sections');
    }
};
