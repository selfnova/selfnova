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
            $table->string('name');
            $table->string('last_name');
            $table->string('alias');
            $table->string('gender');
            $table->bigInteger('born');
            $table->string('passport')->unique();
            $table->string('country');
            $table->string('city');
            $table->text('about');
            $table->string('phone');
            $table->string('site');
            $table->string('real_photo');
            $table->string('avatar')->nullable()->default(null);
            $table->integer('followers')->default(0);
            $table->integer('followings')->default(0);
            $table->string('login')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
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
