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


Route::post('auth/register', 'CustomAuthControllers\AuthController@register');
Route::post('auth/login', 'CustomAuthControllers\AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function(){
  Route::post('auth/logout', 'CustomAuthControllers\AuthController@logout');
  Route::post('/update_profile', 'CustomAuthControllers\UserController@updateProfile');
  Route::post('/update_password', 'CustomAuthControllers\UserController@updatePassword');
  Route::post('post/create', 'Post\PostController@create');
  Route::post('post/update/{post_id}', 'Post\PostController@update');
  Route::post('post/delete/{post_id}', 'Post\PostController@delete');
  Route::post('post/like', 'Like\LikeController@like');
  Route::post('comment/like', 'Like\LikeCommentController@like');
  Route::post('comment/create', 'Comment\CommentController@create');
  Route::post('comment/update/{commentId}', 'Comment\CommentController@update');
  Route::post('comment/delete/{commentId}', 'Comment\CommentController@delete');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
  Route::get('auth/refresh', 'CustomAuthControllers\AuthController@refresh');
});
Route::get('auth/user', 'CustomAuthControllers\AuthController@user');
Route::get('/user/verify/{token}', 'CustomAuthControllers\AuthController@verifyUser');

Route::post('password/reset', 'CustomAuthControllers\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'CustomAuthControllers\ResetPasswordController@reset');
Route::post('password/reset_action', 'CustomAuthControllers\ResetPasswordController@reset_action');

Route::get('posts', 'Post\PostController@list');
Route::get('posts/{post_id}', 'Post\PostController@getPost');
Route::get('post/checkLiked', 'Like\LikeController@isLiked');
Route::get('post/likesCount', 'Like\LikeController@likesCount');
Route::get('postcomments', 'Comment\CommentController@listByPost');
Route::get('usercomments', 'Comment\CommentController@listByUser');
Route::get('comments/{commentId}', 'Comment\CommentController@comments');
Route::get('comment/count', 'Comment\CommentController@postCommentsCount');
Route::get('comment/findByParent', 'Comment\CommentController@findByParent');
Route::get('comment/countReply', 'Comment\CommentController@countReply');
Route::get('comment/canDeleteComment', 'Comment\CommentController@canDeleteComment');
Route::get('comment/canUpdateComment', 'Comment\CommentController@canUpdateComment');
Route::get('comment/checkLiked', 'Like\LikeCommentController@isLiked');
Route::get('comment/likesCount', 'Like\LikeCommentController@likesCount');

