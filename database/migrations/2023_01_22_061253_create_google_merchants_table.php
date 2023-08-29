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
        Schema::create('google_merchants', function (Blueprint $table) {
            $table->id();
            $table->string('platform')->nullable();
            $table->string('name')->nullable();
            $table->string('rating')->nullable();
            $table->string('review', 65,2)->nullable();

            $table->text('hours')->nullable();
            $table->text('social_media')->nullable();
            $table->string('website')->nullable();
            $table->string('number')->nullable();
            $table->string('country')->nullable();
            $table->string('province_county_state')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->boolean('checked')->default(0);
            $table->boolean('invalid')->default(0);

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
        Schema::dropIfExists('google_merchants');
    }
};
