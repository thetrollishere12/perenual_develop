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
        Schema::create('etsy_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('email');

            $table->integer('userId');

            $table->string('bearer_token');

            $table->string('refresh_token');

            $table->timestamp('expires_in')->nullable();

            $table->bigInteger('user_id');

            $table->BigInteger('shop_id');

            $table->string('shop_name')->nullable();

            $table->string('shop_url')->nullable();

            $table->string('shop_icon')->nullable();

            $table->integer('shop_transaction')->nullable();
            
            $table->integer('review_count')->default(0);

            $table->decimal('review_average', 65,2)->default(0);

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
        Schema::dropIfExists('etsy_accounts');
    }
};
