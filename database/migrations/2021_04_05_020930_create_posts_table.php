<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';

            $table->id();
            $table->timestamps();

			$table->string('title', 25);
			$table->text('content');

			$table->softDeletes();

			$table->bigInteger('category');
			$table->bigInteger('thread')->nullable();
			$table->bigInteger('owning_user');

			$table->json('data'); // additional data
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
