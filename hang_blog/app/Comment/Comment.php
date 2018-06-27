<?php

namespace App\Comment;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\User', 'comment_id');
    }

    public function post()
    {
    	return $this->belongsTo('App\Post\Post', 'comment_id');
    }
}
