<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Group extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('u_id');
            $table->boolean('active')->default(true);
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->string('alias')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('about')->nullable();
            $table->string('address')->nullable();
            $table->integer('service');

            $table->string('phone')->nullable();
            $table->string('site')->nullable();
            $table->integer('followers')->default(0);
            $table->float('rating')->default(0.0);
            $table->boolean('verify')->default(false);
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
        Schema::dropIfExists('groups');
    }
}
