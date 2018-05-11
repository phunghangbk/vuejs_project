<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password', 'nickname', 'avatar_image', 'cover_image'];
}
