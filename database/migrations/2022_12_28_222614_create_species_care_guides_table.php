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
        Schema::create('species_care_guides', function (Blueprint $table) {
            $table->id();

            $table->string('common_name');

            $table->text('scientific_name');

            $table->integer('seen')->default(0);
            
            $table->integer('helpful')->default(0);

            // $table->longText('watering')->nullable();

            // $table->longText('pruning')->nullable();

            // $table->longText('sunlight')->nullable();

            // $table->longText('fertilizer')->nullable();

            // $table->longText('toxic')->nullable();

            // $table->longText('maintenance')->nullable();

            // $table->longText('growth_rate')->nullable();

            // $table->longText('fruits')->nullable();

            // $table->longText('hardiness')->nullable();

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
        Schema::dropIfExists('species_care_guides');
    }
};
