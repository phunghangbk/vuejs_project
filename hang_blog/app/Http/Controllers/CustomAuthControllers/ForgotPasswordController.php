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
use Mail;
use App\Mail\ResetPassword as ResetPasswordMail;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => $validator->errors()
            ]);
        }
        try {
            $user = User::where('email', $request->email)->first();
            if (! isset($user)) {
                return response([
                    'status' => config('application.response_status')['error'],
                    'errors' => config('application.invalid_user'),
                    'msg' => config('application.invalid_user')
                ]);
            }

            if ($user->verified == 0) {
                return response([
                    'status' => config('application.response_status')['error'],
                    'errors' => config('application.need_virified_email'),
                    'msg' => config('application.need_virified_email')
                ]);
            }

            $resetPassword = ResetPassword::create([
                'email' => $request->email,
                'token' => str_random(40)
            ]);

            Mail::to($request->email)->send(new ResetPasswordMail($resetPassword->token));

            return response([
                'status' => config('application.response_status')['success'],
                'errors' => [],
                'msg' => ''
            ]);
        } catch (\Exception $e) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => [],
                'msg' => $e->getMessage()
            ]);
        }
    }
}