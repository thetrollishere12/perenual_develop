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
        Schema::create('species_comments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('parent_id')->nullable();
            $table->string('scientific_name')->nullable();
            $table->text('comment');
            $table->longText('user_like')->default('[]');
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
        Schema::dropIfExists('species_comments');
    }
};
