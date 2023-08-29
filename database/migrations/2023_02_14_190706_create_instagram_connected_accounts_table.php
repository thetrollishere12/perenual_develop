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
        Schema::create('instagram_connected_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('account_id');
            $table->string('nickname');
            $table->string('name')->nullable();
            $table->text('email')->nullable();
            $table->longText('user')->nullable();
            $table->longText('attributes')->nullable();
            $table->text('token')->nullable();
            $table->text('refreshToken')->nullable();
            $table->timestamp('expiresIn')->nullable();
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
        Schema::dropIfExists('instagram_connected_accounts');
    }
};
