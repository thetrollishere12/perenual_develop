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
        Schema::create('species_issues', function (Blueprint $table) {
            $table->id();

            $table->string('type')->nullable();
            $table->string('common_name');
            $table->longText('scientific_name')->nullable();
            $table->longText('other_name')->nullable();
            $table->string('family')->nullable();
            $table->longText('description')->nullable();

            $table->longText('effect')->default('[]')->nullable();

            $table->longText('solution')->default('[]')->nullable();
            $table->longText('host')->nullable();

            $table->text('copyright_images')->nullable();
            $table->text('image')->nullable();
            

            $table->text('default_image')->nullable();

            $table->text('folder')->nullable();
            

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
        Schema::dropIfExists('species_issues');
    }
};