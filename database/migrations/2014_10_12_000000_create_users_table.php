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
            $table->id();
            $table->string('username');
            $table->string('nickname');
            $table->string('password');
            $table->string('app_id');
            $table->string('avatar');
            $table->string('email')->nullable()->unique();
            $table->tinyInteger('status')->default(0);
            $table->string('source')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->timestamp('last_login_time')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
