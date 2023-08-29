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

            $table->string('common_name')->nullable();

            $table->text('scientific_name')->unique()->nullable();

            $table->text('other_name')->nullable();

            $table->string('family')->nullable();

            $table->text('origin')->nullable();

            $table->string('type')->nullable();

            $table->string('dimension')->nullable();

            $table->string('cycle')->nullable();

            $table->string('watering')->nullable();

            $table->boolean('flowers')->default(0);

            $table->string('flowering_season')->nullable();

            $table->text('color')->nullable();

            $table->text('hardiness')->nullable();

            $table->text('attracts')->nullable();

            $table->text('propagation')->nullable();

            $table->text('sunlight')->nullable();

            $table->text('soil')->nullable();

            $table->text('problem')->nullable();

            $table->text('pest_susceptibility')->nullable();

            $table->boolean('cones')->default(0);

            $table->boolean('fruits')->default(0);

            $table->text('fruit_color')->nullable();

            $table->string('fruiting_season')->nullable();

            $table->string('harvest_season')->nullable();

            $table->boolean('edible_fruit')->default(0);

            $table->boolean('leaf')->default(0);

            $table->text('leaf_color')->nullable();

            $table->boolean('edible_leaf')->default(0);

            $table->string('growth_rate')->nullable();

            $table->string('maintenance')->nullable();

            $table->boolean('medicinal')->default(0);

            $table->boolean('tropical')->default(0);

            $table->boolean('cuisine')->default(0);

            $table->boolean('indoor')->default(0);

            $table->boolean('poisonous_to_humans')->default(0);

            $table->boolean('poisonous_to_pets')->default(0);
            
            $table->boolean('drought_tolerant')->default(0);

            $table->boolean('salt_tolerant')->default(0);

            $table->boolean('thorny')->default(0);

            $table->boolean('invasive')->default(0);

            $table->boolean('rare')->default(0);

            $table->string('care_level')->nullable();

            $table->longText('description')->nullable();

            $table->text('copyright_image')->nullable();

            $table->text('copyright_image2')->nullable();

            $table->text('image')->nullable();

            $table->text('default_image')->nullable();

            $table->text('folder')->nullable();

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
