<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
			$table->integer('u_id')->nullable();
            $table->integer('g_id')->nullable();
            $table->string('subject')->nullable();;
            $table->text('text')->nullable();
            $table->integer('price')->nullable();
            $table->integer('currency')->nullable();
			$table->json('photos')->nullable();
            $table->float('rating', 3, 2)->nullable();
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
        Schema::dropIfExists('products');
    }
}
