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
        Schema::create('species_comment_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('species_comment_id')->constrained('species_comments')->onDelete('cascade');
            $table->integer('user_id');
            $table->integer('ratings');
            $table->string('scientific_name')->nullable();
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
        Schema::dropIfExists('species_comment_reviews');
    }
};
