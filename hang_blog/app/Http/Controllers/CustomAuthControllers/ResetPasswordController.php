<?php

namespace App\Http\Controllers\CustomAuthControllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use JWTAuth;
use App\Model\User;
use App\Model\ResetPassword;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function reset($token)
    {
        $resetPassword = ResetPassword::where('token', $token)->first();
        if (isset($resetPassword)) {
            $now = Carbon::now();
            $created_at = $resetPassword->created_at;
            if ($created_at == null || $now->diffInHours($created_at) > 1) {
                return response([
                    'status' => config('application.response_status')['error'],
                    'message' => config('application.expired_token'),
                    'user' => null
                ]);
            }

            $user = User::where('email', $resetPassword->email)->first();
            if (! isset($user)) {
                return response([
                    'status' => config('application.response_status')['error'],
                    'message' => config('application.invalid_user'),
                    'user' => null
                ]);
            }

            return response([
                'status' => config('application.response_status')['success'],
                'message' => '',
                'user' => $user
            ]);
        }
        return response([
            'status' => config('application.response_status')['error'],
            'message' => config('application.invalid_user'),
            'user' => null
        ]);
    }

    public function reset_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:6|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => $validator->errors(),
            ]);
        }
        try {
            $user = User::where('email', $request->email)->first();
            if (! isset($user))
            {
                return response([
                    'status' => config('application.response_status')['error'],
                    'errors' => ['error' => config('application.invalid_user')]
                ]);
            }

            $user->password = bcrypt($request->password);
            $user->created_at = Carbon::now();

            $user->save();
            return response([
                'status' => config('application.response_status')['success'],
                'errors' => []
            ]);
        } catch(\Exception $e) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => ['error' => $e->getMessage()]
            ]);
        }
    }
}