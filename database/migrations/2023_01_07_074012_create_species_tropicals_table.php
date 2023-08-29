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
        Schema::create('species_tropicals', function (Blueprint $table) {
            $table->id();

            $table->string('common_name')->nullable();

            $table->text('scientific_name')->unique()->nullable();

            $table->text('other_name')->nullable();

            $table->string('family')->nullable();

            $table->text('origin')->nullable();

            $table->string('type')->nullable();

            $table->string('dimension')->nullable();

            $table->string('cycle')->nullable();

            $table->string('watering')->nullable();

            $table->string('edible')->nullable();

            $table->text('attracts')->nullable();

            $table->text('propagation')->nullable();

            $table->text('hardiness')->nullable();

            $table->boolean('flowers')->default(0);

            $table->string('flowering_season')->nullable();

            $table->text('color')->nullable();

            $table->string('sun_exposure')->nullable();

            $table->text('soil')->nullable();

            $table->text('pest_susceptibility')->nullable();

            $table->boolean('fruits')->default(0);

            $table->string('fruiting_season')->nullable();

            $table->string('poisonous')->nullable();

            $table->string('growth_rate')->nullable();
            
            $table->string('maintenance')->nullable();

            $table->longText('description')->nullable();

            $table->string('image')->nullable();

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
        Schema::dropIfExists('species_tropicals');
    }
};
