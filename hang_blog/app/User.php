<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Model implements Authenticatable
{
	use AuthenticableTrait;
	
    protected $table = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password', 'nickname', 'avatar_image', 'cover_image'];
}
