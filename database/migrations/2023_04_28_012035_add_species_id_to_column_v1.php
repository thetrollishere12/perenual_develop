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
        Schema::table('species_care_guides', function (Blueprint $table) {
            $table->integer('species_id')->nullable()->after('id');
        });

        Schema::table('species_images', function (Blueprint $table) {
            $table->integer('species_id')->nullable()->after('id');
        });

        Schema::table('species_comments', function (Blueprint $table) {
            $table->integer('species_id')->nullable()->after('id');
        });

        Schema::table('species_comment_reviews', function (Blueprint $table) {
            $table->integer('species_id')->nullable()->after('id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('species_care_guides', function (Blueprint $table) {
            $table->dropColumn('species_id');
        });

        Schema::table('species_images', function (Blueprint $table) {
            $table->dropColumn('species_id');
        });

        Schema::table('species_comments', function (Blueprint $table) {
            $table->dropColumn('species_id');
        });

        Schema::table('species_comment_reviews', function (Blueprint $table) {
            $table->dropColumn('species_id');
        });
    }
};
