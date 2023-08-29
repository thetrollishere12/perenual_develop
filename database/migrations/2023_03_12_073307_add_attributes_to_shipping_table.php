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
        Schema::table('shipping_domestics', function (Blueprint $table) {
            $table->longText('attributes')->nullable();
        });

        Schema::table('shipping_internationals', function (Blueprint $table) {
            $table->longText('attributes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_domestics', function (Blueprint $table) {
            $table->longText('attributes')->nullable();
        });

        Schema::table('shipping_internationals', function (Blueprint $table) {
            $table->longText('attributes')->nullable();
        });
        
    }
};
