<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDtCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dt_comments', function (Blueprint $table) {
            $table->increments('comment_id')->unsigned()->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('post_id');
            $table->text('content');
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->foreign('user_id')->references('user_id')->on('mst_user')->onDeletes('cascade');
            $table->foreign('post_id')->references('post_id')->on('mst_post')->onDeletes('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dt_comments');
    }
}
