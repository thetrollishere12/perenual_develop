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
            
            $table->text('fruiting_month_description')->nullable()->after('fruiting_month');
            $table->text('flowering_month_description')->nullable()->after('flowering_month');
            $table->text('harvesting_month_description')->nullable()->after('harvesting_month');

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
            $table->dropColumn('fruiting_month_description');
            $table->dropColumn('flowering_month_description');
            $table->dropColumn('harvesting_month_description');
        });
    }
};
