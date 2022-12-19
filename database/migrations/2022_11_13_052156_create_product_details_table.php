<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('cycle')->nullable();
            $table->decimal('width', 65,2)->nullable();
            $table->decimal('height', 65,2)->nullable();
            $table->string('watering')->nullable();
            $table->string('sun_exposure')->nullable();
            $table->string('origin')->nullable();
            $table->text('color')->nullable();
            $table->string('pet_friendly')->nullable();
            $table->string('poisonous')->nullable();
            $table->string('edible')->nullable();
            $table->text('suitable_location')->nullable();
            $table->string('maintenance')->nullable();
            $table->string('growth_rate')->nullable();
            $table->string('flowering_season')->nullable();
            $table->string('fruiting_season')->nullable();
            $table->string('fertilizer')->nullable();
            $table->string('humidity')->nullable();
            $table->text('soil')->nullable();
            $table->text('hardiness')->nullable();
            $table->string('pruning')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details');
    }
}
