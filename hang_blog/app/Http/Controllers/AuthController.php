<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAtuh;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request) {
    	$user = new User;
    	$user->email = $request->email;
    	$user->name = $request->name;
    	$user->password = bcrypt($requst->password);
    	$user->save();

    	return response([
    		'status' => 'success',
    		'data' => $user
    	], 200);
    }

    public function login(Request $request) {
    	$credentials = $request->only('email', 'password');

    	if (! $token = JWTAuth::attemp($credentials)) {
    		return response([
    			'status' => 'error',
    			'error' => 'invalid.credentials',
    			'msg' => 'Invalid Credentials.'
    		], 400);
    	}

    	return response([
    		'status' => 'success',
    	])->header('Authorization', $token);
    }

    public function user(Request $request) {
    	$user = User::find(Auth::user()->id);
    	return resposne([
    		'status' => 'success',
    		'data' => $user
    	]);
    }

    public function refresh() {
    	return resposne([
    		'status' => 'success'
    	]);
    }


    public function logout() {
    	JWTAuth::invalidate();

    	return response([
    		'status' => 'success',
    		'msg' => 'Logged out Successfully.'
    	], 200);
    }
}
