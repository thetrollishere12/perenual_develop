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
        Schema::create('species_nutrition_facts', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable()->default(0);
            $table->integer('species_id');

            $table->string('plant_part');

            $table->string('serving_size');
            $table->double('calories');
            $table->double('total_fat');
            $table->double('saturated_fat')->nullable();
            $table->double('trans_fat')->nullable();
            $table->double('monounsaturated_fat')->nullable();
            $table->double('polyunsaturated_fat')->nullable();
            $table->double('omega_3')->nullable();
            $table->double('omega_6')->nullable();
            $table->double('cholesterol');
            $table->double('sodium');
            $table->double('total_carbohydrate');
            $table->double('dietary_fiber')->nullable();
            $table->double('soluble_fiber')->nullable();
            $table->double('insoluble_fiber')->nullable();
            $table->double('sugars')->nullable();
            $table->double('starch')->nullable();
            $table->double('protein');
            $table->double('vitamin_d')->nullable();
            $table->double('calcium')->nullable();
            $table->double('iron')->nullable();
            $table->double('potassium')->nullable();
            $table->double('vitamin_a')->nullable();
            $table->double('vitamin_c')->nullable();
            $table->double('vitamin_e')->nullable();
            $table->double('vitamin_k')->nullable();
            $table->double('thiamin')->nullable();
            $table->double('riboflavin')->nullable();
            $table->double('niacin')->nullable();
            $table->double('vitamin_b6')->nullable();
            $table->double('folate')->nullable();
            $table->double('vitamin_b12')->nullable();
            $table->double('biotin')->nullable();
            $table->double('pantothenic_acid')->nullable();
            $table->double('phosphorus')->nullable();
            $table->double('iodine')->nullable();
            $table->double('magnesium')->nullable();
            $table->double('zinc')->nullable();
            $table->double('selenium')->nullable();
            $table->double('copper')->nullable();
            $table->double('manganese')->nullable();
            $table->double('chromium')->nullable();
            $table->double('molybdenum')->nullable();
            $table->double('chloride')->nullable();
            $table->double('fluoride')->nullable();
            $table->double('choline')->nullable();
            $table->double('phytosterols')->nullable();
            $table->double('caffeine')->nullable();
            $table->double('theobromine')->nullable();
            $table->double('vitamin_b5')->nullable();
            $table->double('vitamin_b7')->nullable();
            $table->double('chlorophyll')->nullable();
            $table->double('inositol')->nullable();
            $table->double('paba')->nullable();
            $table->double('quercetin')->nullable();
            $table->double('rutin')->nullable();
            $table->double('lycopene')->nullable();
            $table->double('lutein_zeaxanthin')->nullable();
            $table->double('betaine')->nullable();

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
        Schema::dropIfExists('species_nutrition_facts');
    }
};
