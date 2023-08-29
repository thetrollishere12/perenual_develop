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
            $table->dropColumn('edible_flower');
            $table->dropColumn('flower_flavor_profile');
            $table->dropColumn('flower_flavor_description');
            $table->dropColumn('leaf_flavor_profile');
            $table->dropColumn('leaf_flavor_description');
            $table->dropColumn('fruit_flavor_profile');
            $table->dropColumn('fruit_flavor_description');
            $table->dropColumn('fruiting_month');
            $table->dropColumn('flowering_month');
            $table->dropColumn('harvesting_month');
        });
    }
};
