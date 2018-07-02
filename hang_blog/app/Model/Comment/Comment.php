<?php

namespace App\Model\Comment;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'dt_comments';
	protected $primaryKey = 'comment_id';
	protected $fillable = ['user_id', 'post_id', 'content','parent_id'];

    public function user()
    {
    	return $this->belongsTo('App\Model\User', 'user_id');
    }

    public function post()
    {
    	return $this->belongsTo('App\Model\Post\Post', 'post_id');
    }

    public function likes()
    {
        return $this->hasMany('App\Model\Like\LikeComment', 'comment_id');
    }

    public function replies() {
        return $this->hasMany('App\Model\Comment\Comment', 'parent_id');
    }
}
