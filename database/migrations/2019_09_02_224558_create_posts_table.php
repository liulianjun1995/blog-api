<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->integer('user_id')->default(0);
            $table->string('title');
            $table->integer('category_id')->default(0);
            $table->string('abstract');
            $table->string('cover')->nullable();
            $table->text('content');
            $table->tinyInteger('top')->default(0);
            $table->tinyInteger('recommend')->default(0);
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->integer('views')->default(0);
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
