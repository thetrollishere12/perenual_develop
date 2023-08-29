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
        Schema::create('species_unauthorizeds', function (Blueprint $table) {

            $table->id();

            $table->integer('other_id')->nullable();

            $table->string('common_name')->nullable();

            $table->text('scientific_name')->nullable();

            $table->text('other_name')->nullable();

            $table->string('genus')->nullable();

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

            $table->longText('copyright_images')->nullable();

            $table->text('image')->nullable();

            $table->text('default_image')->nullable();

            $table->text('folder')->nullable();

            $table->text('tags')->nullable();

            $table->integer('contributed_user_id')->nullable();

            $table->boolean('edible_flower')->default(0);

            $table->text('flower_flavor_profile')->nullable();
            $table->text('flower_flavor_description')->nullable();
            $table->text('leaf_flavor_profile')->nullable();
            $table->text('leaf_flavor_description')->nullable();
            $table->text('fruit_flavor_profile')->nullable();
            $table->text('fruit_flavor_description')->nullable();


            $table->text('fruiting_month')->nullable();

            $table->text('flowering_month')->nullable();

            $table->text('harvesting_month')->nullable();

            $table->text('fruiting_month_description')->nullable();

            $table->text('flowering_month_description')->nullable();

            $table->text('harvesting_month_description')->nullable();

            $table->longText('attributes')->nullable();

            $table->string('source')->nullable();

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
        Schema::dropIfExists('species_unauthorizeds');
    }
};
