<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('alias')->nullable();
            $table->string('gender')->nullable();
            $table->bigInteger('born')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('about')->nullable();
            $table->string('phone')->nullable();
            $table->string('site')->nullable();
            $table->string('avatar')->nullable()->default(null);
            $table->integer('followers')->default(0);
            $table->integer('followings')->default(0);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('verify')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
