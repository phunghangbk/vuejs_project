<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\User;
use Illuminate\Support\Facades\Log;
use Auth;
use Validator;
use App\VerifyUser;
use Mail;
use App\Mail\VerifyMail;

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
            'password.regex' => config('application.password_regex')
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
        try {
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

            $verifyUser = VerifyUser::create([
                'user_id' => $user->user_id,
                'token' => str_random(40)
            ]);

            Mail::to($user->email)->send(new VerifyMail($user));

            return response([
                'status' => config('application.response_status')['success'],
                'user' => $user,
                'errors' => [],
               ], 200);
        } catch (\Exception $e) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()],
               ]);
        }
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
        $user = User::where('email', $request->email)->first();
        if (! isset($user)) {
            return response([
                'status' => config('application.response_status')['error'],
                'error' => config('application.invalid_iredentials'),
                'msg' => config('application.invalid_iredentials')
            ]);
        }

        if ($user->verified == 0) {
            return response([
                'status' => config('application.response_status')['error'],
                'error' => config('application.need_virified_email'),
                'msg' => config('application.need_virified_email')
            ]);
        }

        $credentials = $request->only('email', 'password');
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return response([
                'status' => config('application.response_status')['error'],
                'error' => config('application.invalid_iredentials'),
                'msg' => config('application.invalid_iredentials')
            ]);
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

    public function verifyUser($token)
    {
        $verifyUser = VerifyUser::where('token', $token)->first();
        $authentication_token = null;
        if (isset($verifyUser) ){
            $user = $verifyUser->user;
            if(! $user->verified) {
                $authentication_token = JWTAuth::fromUser($user);
                $verifyUser->user->verified = 1;
                $verifyUser->user->save();
                $message = config('application.verified_email');
            } else {
                $message = config('application.already_verified_email');
            }

            return response([
                'status' => config('application.response_status')['success'],
                'message' => $message,
                'authentication_token' => $authentication_token
            ]);
        }
        return response([
            'status' => config('application.response_status')['error'],
            'message' => config('application.not_verified_email'),
            'authentication_token' => null
        ]);
    }
}
