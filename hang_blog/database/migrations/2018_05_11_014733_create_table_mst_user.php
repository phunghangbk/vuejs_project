<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateTableMstUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_user', function (Blueprint $table) {
            $table->increments('user_id')->unsigned()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nickname')->unique();
            $table->string('avatar_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('roles')->default(1); // 1: user, 2: admin
            $table->boolean('verified')->default(false);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_user');
    }
}
