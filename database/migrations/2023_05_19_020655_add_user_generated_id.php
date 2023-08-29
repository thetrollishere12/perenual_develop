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
        Schema::table('species_care_guide_sections', function (Blueprint $table) {
            $table->integer('generated_user_id')->after('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('species_care_guide_sections', function (Blueprint $table) {
            $table->dropColumn('generated_user_id');
        });
    }
};
