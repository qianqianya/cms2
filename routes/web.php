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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//注册
Route::any('/reg', 'User\UserController@reg');

//登陆
Route::post('/api', 'User\UserController@login');

//退出
Route::post('/quit', 'User\UserController@quit');

//验证token
Route::post('/token', 'User\UserController@token');

//文章
Route::any('/article', 'Article\ArticleController@article');