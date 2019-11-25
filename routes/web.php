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



Route::get('/', "HomeController@index");
Route::get('/post/{slug}', "HomeController@show")->name("post.show");
Route::get('/tag/{slug}', "HomeController@tag")->name("tag.show");
Route::get('/category/{slug}', "HomeController@category")->name("category.show");
Route::post('/subscriptions', 'SubscriptionsController@subscribe');
Route::get('/verify/{token}', 'SubscriptionsController@verify');
Route::post('/push','PushController@store');

Route::group(["middleware" => "auth"], function () {
    Route::get('/logout', 'AuthController@logout');
    Route::get('/profile', 'ProfileController@index');
    Route::post('/profile', 'ProfileController@store');
    Route::post('/comment', 'CommentsController@store');
});

Route::group(["middleware" => "guest"], function () {
    Route::get('/register', 'AuthController@registerForm');
    Route::post('/register', 'AuthController@register');
    Route::get('/login', 'AuthController@loginForm')->name("login");
    Route::post('/login', 'AuthController@login');
});