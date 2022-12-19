<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpeciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('species', function (Blueprint $table) {
            $table->id();
            $table->string('common_name');
            $table->string('scientific_name');
            $table->string('image')->nullable();

            $table->decimal('width', 65,2)->nullable();
            $table->decimal('height', 65,2)->nullable();

            $table->text('hardiness')->nullable();
            $table->string('type')->nullable();
            $table->string('watering')->nullable();
            $table->string('sun_exposure')->nullable();
            $table->string('pruning')->nullable();
            
            $table->string('humidity')->nullable();
            $table->text('soil')->nullable();
    
            $table->string('flowering_season')->nullable();
            $table->string('fruiting_season')->nullable();

            $table->string('rare')->nullable();
            $table->string('poisonous')->nullable();
            $table->string('fruits')->nullable();
            $table->string('edible')->nullable();

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
        Schema::dropIfExists('species');
    }
}
