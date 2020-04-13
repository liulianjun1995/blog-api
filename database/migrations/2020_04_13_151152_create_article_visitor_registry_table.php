<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleVisitorRegistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_visitor_registry', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id');
            $table->string('ip', 32);
            $table->string('country')->nullable();
            $table->integer('clicks')->unsigned()->default(0);
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
        Schema::table('article_visitor_registry', function (Blueprint $table) {
            //
        });
    }
}
