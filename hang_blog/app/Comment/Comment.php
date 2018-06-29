<?php

namespace App\Comment;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'dt_comments';
	protected $primaryKey = 'comment_id';
	protected $fillable = ['user_id', 'post_id', 'content','parent_id'];

    public function user()
    {
    	return $this->belongsTo('App\User', 'user_id');
    }

    public function post()
    {
    	return $this->belongsTo('App\Post\Post', 'post_id');
    }
}
