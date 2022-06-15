<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViews extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('views', function (Blueprint $table) {
			$table->id();
			$table->foreignId('post_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

			$table->foreignId('viewer_id')->constrained('users')->cascadeOnDelete();

			$table->timestamp('created_at');
		});

		Schema::table('posts', function (Blueprint $table) {
			$table->unsignedBigInteger('views')->default(0);
		});
		Schema::table('groups', function (Blueprint $table) {
			$table->unsignedBigInteger('views')->default(0);
		});
		Schema::table('users', function (Blueprint $table) {
			$table->unsignedBigInteger('views')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('views');

		Schema::table('posts', function (Blueprint $table) {
			$table->dropColumn('views');
		});
		Schema::table('groups', function (Blueprint $table) {
			$table->dropColumn('views');
		});
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('views');
		});
	}
}
