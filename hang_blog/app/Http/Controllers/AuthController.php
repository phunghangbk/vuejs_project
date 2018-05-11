<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Log;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Validator::make($request->all(), [
                'email' => 'required|email|unique',
                'password' => 'required|string|min:6|max:10',

            ], 
            $messages
        );
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->password = bcrypt($request->password);
        $user->nickname = $request->nickname;
        $user->avatar_image = $request->avatar_image;
        $user->cover_image = $request->cover_image;
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
           ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credentials',
                'msg' => 'Invalid Credentials.'
            ], 400);
        }
        return response([
            'status' => 'success'
        ])->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => 'success'
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }
}
