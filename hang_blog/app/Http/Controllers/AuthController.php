<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Log;
use Auth;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input data
        $rules = [
            'email' => 'required|string|email|max:255|unique:mst_user',
            'password'=> 'required|min:6|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ];
        $messages = [
            'password.regex' => 'Password needs to contain at least one uppercase, lowercase letters and one number'
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            Log::info($validator->errors());
            return response([
                'status' => config('application.response_status')['error'],
                'data' => null,
                'errors' => $validator->errors()
            ]);
        }

        // store user information to database
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        $user->nickname = $request->nickname;
        $avatar_image_name = '';
        $cover_image_name = '';

        //avatar image
        if ($request->avatar_image !== '' && $request->avatar_image !== null) {
            $avatar_image_name = time().'.' . explode('/', explode(':', substr($request->avatar_image, 0, strpos($request->avatar_image, ';')))[1])[1];
            $avatar_image = \Image::make($request->avatar_image);
            $avatar_image->resize(720,720);
            $avatar_image->save(public_path('avatar_images/').$avatar_image_name);
        }
        

        //cover image
        if ($request->cover_image !== '' && $request->cover_image !== null) {
            $cover_image_name = time().'.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];
            $cover_image = \Image::make($request->cover_image);
            $cover_image->resize(1000, 1000);
            $cover_image->save(public_path('cover_images/').$cover_image_name);
        }

        $user->avatar_image = $avatar_image_name;
        $user->cover_image = $cover_image_name;
        $user->save();

        // create JWT token
        Log::info($user);
        $token = JWTAuth::fromUser($user);

        return response([
            'status' => config('application.response_status')['success'],
            'user' => $user,
            'errors' => [],
            'token' => $token
           ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response([
                'status' => config('application.response_status')['error'],
                'token' => null,
                'errors' => $validator->errors()
            ]);
        }

        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => config('application.response_status')['error'],
                'error' => config('application.invalid_iredentials'),
                'msg' => config('application.invalid_iredentials')
            ], 400);
        }

        return response([
            'status' => config('application.response_status')['success'],
            'token' => $token,
            'user' => Auth::user(),
        ])->header('Authorization', $token);
    }

    public function user(Request $request)
    {
        return response()->json([
            'status' => config('application.response_status')['success'],
            'user' => Auth::user()
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
