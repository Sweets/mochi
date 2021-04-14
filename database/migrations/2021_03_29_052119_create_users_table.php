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
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            $table->string('name', 16)->unique();
            $table->string('email')->unique();
            $table->string('password'); // hash
			$table->string('api_token')->unique();

            $table->dateTime('registered');
            $table->dateTime('last_active');

            $table->json('data'); // group data, moderation data
            $table->json('statistics'); // post stats
            $table->json('settings'); // user settings

            $table->rememberToken();
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
