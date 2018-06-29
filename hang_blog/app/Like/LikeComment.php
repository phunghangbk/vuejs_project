<?php

namespace App\Like;

use Illuminate\Database\Eloquent\Model;

class LikeComment extends Model
{
    protected $table = 'dt_like_comments';
    protected $fillable = ['user_id', 'comment_id'];
}
