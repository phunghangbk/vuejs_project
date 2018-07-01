<?php

namespace App\Model\Post;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'mst_post';
    protected $primaryKey = 'post_id';
    protected $fillable = ['user_id', 'title', 'image', 'content', 'status', 'introduction'];

    public function user()
    {
        return $this->belongsTo('App\Model\User', 'post_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Model\Comment\Comment', 'post_id');
    }
}
