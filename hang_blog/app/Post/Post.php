<?php

namespace App\Post;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'mst_post';
    protected $primaryKey = 'post_id';
    protected $fillable = ['title', 'image', 'content', 'status', 'introduction'];
}
