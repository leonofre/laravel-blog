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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts/single/{slug}', 'PostController@show' );
Route::get('/posts/{posts_count}/{page}', 'PostController@index' );
Route::post('/posts/{posts_count}/{page}', 'PostController@search' );

Route::middleware('auth:api')->get('/user/posts/{posts_count}/{page}', 'PostController@user_posts' );
Route::middleware('auth:api')->post('/user/posts/{posts_count}/{page}', 'PostController@user_search' );
Route::middleware('auth:api')->get('/user/post/{id}', 'PostController@user_post' );
Route::middleware('auth:api')->post('/user/post', 'PostController@store' );
Route::middleware('auth:api')->post('/user/post/{id}', 'PostController@update' );
Route::middleware('auth:api')->delete('/user/post/{id}', 'PostController@destroy' );

Route::get('/authors', 'AuthorController@index' );
