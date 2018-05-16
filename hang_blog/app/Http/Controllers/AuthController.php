<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use App\Http\Requests\RegisterFormRequest;
use Illuminate\Support\Facades\Log;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->password = bcrypt($request->password);
        $user->nickname = $request->nickname;
        $avatar_image_name = time().'.' . explode('/', explode(':', substr($request->avatar_image, 0, strpos($request->avatar_image, ';')))[1])[1];
        \Image::make($request->avatar_image)->save(public_path('avatar_images/').$avatar_image_name);
        $cover_image_name = time().'.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];
        \Image::make($request->cover_image)->save(public_path('cover_images/').$cover_image_name);
        $user->avatar_image = $avatar_image_name;
        $user->cover_image = $cover_image_name;
        $user->save();
        return response([
            'status' => config('application.response_status')['success'],
            'data' => $user
           ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => config('application.response_status')['error'],
                'error' => config('application.invalid_iredentials'),
                'msg' => config('application.invalid_iredentials')
            ], 400);
        }
        return response([
            'status' => config('application.response_status')['success']
        ])->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response([
            'status' => config('application.response_status')['success'],
            'data' => $user
        ]);
    }

    public function refresh()
    {
        return response([
            'status' => config('application.response_status')['success']
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate();
        return response([
            'status' => config('application.response_status')['success'],
            'msg' => 'Logged out Successfully.'
        ], 200);
    }
}
