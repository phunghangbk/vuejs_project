<?php

namespace App\Model\Like;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = 'dt_post';
    protected $fillable = ['user_id', 'post_id'];
    
    public function post()
    {
    	return $this->belongsTo('App\Model\Post\Post', 'post_id');
    }
}
