<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RegisterOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_orders', function (Blueprint $table) {
			$table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('gender');
            $table->bigInteger('born');
            $table->string('passport')->unique();
            $table->string('country');
            $table->string('city');
            $table->string('real_photo');
            $table->integer('active');
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
        Schema::dropIfExists('register_orders');
    }
}
