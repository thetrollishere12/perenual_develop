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
        Schema::create('species_care_guide_sections', function (Blueprint $table) {
            $table->id();

            $table->integer('guide_id');

            $table->string('type');

            $table->longText('description')->nullable();

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
        Schema::dropIfExists('species_care_guide_sections');
    }
};
