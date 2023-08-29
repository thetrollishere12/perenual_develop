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
        Schema::table('species', function (Blueprint $table) {
            
            $table->boolean('seeds')->default(0)->after('harvest_season');

            $table->boolean('edible_seeds')->after('seeds')->default(0);

            $table->text('seeds_flavor_profile')->after('edible_seeds')->nullable();
            $table->text('seeds_flavor_description')->after('seeds_flavor_profile')->nullable();

            $table->longText('dimensions')->after('dimension')->nullable()->default('[]');






            // Like once in winter but twice in summmer
            // $table->text('watering_monthly_frequency')->nullable()->after('watering');


            // If need distilled water etc
            // $table->string('watering_quality')->nullable()->after('watering');

            // $table->string('soil_moisture_amount')->nullable()->after('soil');




            // General Watering Benchmark
            $table->string('watering_general_benchmark')->nullable()->after('watering');

            // If water in the morning etc
            $table->string('watering_period')->nullable()->after('watering');

            // How much water in ml
            $table->text('volume_water_requirement')->nullable()->default('[]')->after('watering');

            // How much water in inches
            $table->text('depth_water_requirement')->nullable()->default('[]')->after('watering');





            // How long in hours etc
            $table->text('sunlight_duration')->nullable()->default('[]')->after('sunlight');

            $table->string('sunlight_period')->nullable()->after('sunlight');

            $table->string('pruning')->nullable()->after('harvesting_month_description');
            $table->text('pruning_count')->nullable()->default('[]')->after('harvesting_month_description');

            // Change to actual measurements
            // $table->string('pruning_takeoff_amount')->nullable()->after('harvesting_month_description');

            $table->text('pruning_month')->nullable()->after('harvesting_month_description');
            // $table->text('pruning_season')->nullable()->after('harvesting_month_description');


            $table->longText('plant_anatomy')->default('[]')->nullable()->after('propagation');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('species', function (Blueprint $table) {
            //
        });
    }
};
