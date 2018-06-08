<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::any('{all}', function () { return view('welcome'); })->where(['all' => '.*']);
Route::post('auth/register', 'AuthController@register');
Route::get('user/verify/{token}', 'AuthController@verifyUser');