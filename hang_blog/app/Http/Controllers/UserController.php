<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use JWTAuth;
use App\User;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password'=> 'required|min:6|string|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
        ]);

        $messages = [
            'password.regex' => 'Password needs to contain at least one uppercase, lowercase letters and one number'
        ];

        if ($validator->fails()) {
            return response([
                'status' => config('application.response_status')['error'],
                'errors' => $validator->errors()
            ]);
        }
        try {
            $user = Auth::user();
            if (! empty($request->name)) {
                $user->name = $request->name;
            }

            if (! empty($request->password)) {
                $user->password = bcrypt($request->password);
            }

            if (! empty($request->nickname)) {
                $user->nickname = $request->nickname;
            }
            

            $avatar_image_name = '';
            $cover_image_name = '';

            //avatar image
            if (! empty($request->avatar_image)) {
                $avatar_image_name = time().'.' . explode('/', explode(':', substr($request->avatar_image, 0, strpos($request->avatar_image, ';')))[1])[1];
                $avatar_image = \Image::make($request->avatar_image);
                $avatar_image->resize(720,720);
                $avatar_image->save(public_path('avatar_images/').$avatar_image_name);
                $user->avatar_image = $avatar_image_name;
            }
            
            //cover image
            if (! empty($request->cover_image)) {
                $cover_image_name = time().'.' . explode('/', explode(':', substr($request->cover_image, 0, strpos($request->cover_image, ';')))[1])[1];
                $cover_image = \Image::make($request->cover_image);
                $cover_image->resize(1000, 1000);
                $cover_image->save(public_path('cover_images/').$cover_image_name);
                $user->cover_image = $cover_image_name;
            }

            $user->save();
            return response()->json([
                'user' => $user,
                'status' => config('application.response_status')['success']
            ]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'status' => config('application.response_status')['error'],
                'errors' => $e->getMessage()
            ]);
        }
    }
}
