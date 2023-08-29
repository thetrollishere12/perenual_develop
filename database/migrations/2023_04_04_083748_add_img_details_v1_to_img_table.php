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

        Schema::table('article_faq_images', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('license_url');
            $table->longText('alt')->nullable()->after('license_url');
        });

        Schema::table('disease_images', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('license_url');
            $table->longText('alt')->nullable()->after('license_url');
        });

        Schema::table('species_images', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('license_url');
            $table->longText('alt')->nullable()->after('license_url');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('article_faq_images', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('alt');
        });

        Schema::table('disease_images', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('alt');
        });

        Schema::table('species_images', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('alt');
        });
        
    }
};
