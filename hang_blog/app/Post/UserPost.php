<?php

namespace App\Post;

use Illuminate\Database\Eloquent\Model;

class UserPost extends Model
{
    protected $table = 'dt_post';
    protected $fillable = ['user_id', 'post_id', 'type'];
}
