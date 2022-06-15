<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class News extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('alias');
            $table->integer('active')->default(0);
            $table->string('photo');
            $table->string('banner')->nullable();
            $table->text('content')->nullable();
            $table->text('short_cont')->nullable();
            $table->text('description')->nullable();
            $table->string('title')->nullable();
            $table->string('alt')->nullable();

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
		Schema::dropIfExists('news');
    }
}
