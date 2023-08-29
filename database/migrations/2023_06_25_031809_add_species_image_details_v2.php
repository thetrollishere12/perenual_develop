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
        Schema::table('species_images', function (Blueprint $table) {
            $table->longText('plant_image_anatomy')->default('[]')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('species_images', function (Blueprint $table) {
            $table->dropColumn('plant_image_anatomy');
        });
    }
};
