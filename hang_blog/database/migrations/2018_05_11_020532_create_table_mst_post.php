<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMstPost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mst_post', function (Blueprint $table) {
            $table->increments('post_id')->unsigned()->unique();
            $table->unsignedInteger('user_id');
            $table->string('title')->default('');
            $table->string('image')->nullable();
            $table->text('content')->default('');
            $table->integer('status')->default(1); //1: published 2: hidden
            $table->text('introduction')->default('');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('user_id')->references('user_id')->on('mst_user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_post');
    }
}
