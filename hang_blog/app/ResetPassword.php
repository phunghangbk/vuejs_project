<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $guarded = [];
 	protected $table = 'password_resets';
 	protected $fillable = ['email', 'token', 'created_at'];

 	public function setUpdatedAt($value){
        ;
    }
}