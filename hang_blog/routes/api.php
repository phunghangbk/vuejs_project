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
  Route::post('post/create', 'Post\PostController@create');
  Route::post('post/update/{post_id}', 'Post\PostController@update');
  Route::post('post/delete/{post_id}', 'Post\PostController@delete');
  Route::post('post/like', 'Like\LikeController@like');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'AuthController@refresh');
});
Route::get('auth/user', 'AuthController@user');
Route::get('/user/verify/{token}', 'AuthController@verifyUser');

Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'ResetPasswordController@reset');
Route::post('password/reset_action', 'ResetPasswordController@reset_action');

Route::get('post/list', 'Post\PostController@list');
Route::get('post/{post_id}', 'Post\PostController@getPost');
Route::get('post/checkLiked', 'Like\LikeController@isLiked');
Route::get('post/likesCount', 'Like\LikeController@likesCount');


