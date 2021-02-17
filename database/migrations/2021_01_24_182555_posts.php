<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Posts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id')->nullable();
            $table->integer('g_id')->nullable();
            $table->integer('repost_id')->nullable();
            $table->string('type')->default('post');
            $table->string('subject');
            $table->text('text')->nullable();
			$table->json('photos')->nullable();
            $table->string('video')->nullable();
            $table->string('music')->nullable();
            $table->json('options')->nullable();
            $table->float('rating', 3, 2)->nullable();
            $table->integer('count_comm')->default(0);
            $table->integer('count_repost')->default(0);
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
        Schema::dropIfExists('posts');
    }
}
