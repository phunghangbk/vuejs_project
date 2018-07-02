<?php

namespace App\Model\Like;

use Illuminate\Database\Eloquent\Model;

class LikeComment extends Model
{
    protected $table = 'dt_like_comments';
    protected $fillable = ['user_id', 'comment_id'];

    public function comment()
    {
    	return $this->belongsTo('App\Model\Comment\Comment', 'comment_id');
    }
}
