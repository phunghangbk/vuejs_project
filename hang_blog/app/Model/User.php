<?php

namespace App\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
	use Notifiable;

    protected $table = 'mst_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['name', 'email', 'password', 'nickname', 'avatar_image', 'cover_image'];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function verifyUser()
    {
        return $this->hasOne('App\Model\VerifyUser', 'user_id');
    }

    public function posts()
    {
        return $this->hasMany('App\Model\Post\Post', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Model\Comment\Comment', 'user_id');
    }
}
