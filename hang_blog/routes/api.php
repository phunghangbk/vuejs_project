<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/register', 'AuthController@register');
Route::post('auth/login', 'AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function(){
  Route::post('auth/logout', 'AuthController@logout');
  Route::post('/update_profile', 'UserController@updateProfile');
  Route::post('/update_password', 'UserController@updatePassword');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
Route::get('auth/user', 'AuthController@user');
Route::get('/user/verify/{token}', 'AuthController@verifyUser');

Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'ResetPasswordController@reset');
Route::post('password/reset_action', 'ResetPasswordController@reset_action');
