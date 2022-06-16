<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monet_orders', function (Blueprint $table) {
            $table->id();

			$table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
			$table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();

			$table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();

			$table->boolean('is_approved')->default(false);

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
        Schema::dropIfExists('monet_orders');
    }
}
