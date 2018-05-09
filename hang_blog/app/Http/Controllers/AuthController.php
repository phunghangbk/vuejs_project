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
        $credentials = $request->only('email', 'password'); // grab credentials from the request
        try {
            if (!$token = JWTAuth::attempt($credentials)) { // attempt to verify the credentials and create a token for the user
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500); // something went wrong whilst attempting to encode the token
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
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
