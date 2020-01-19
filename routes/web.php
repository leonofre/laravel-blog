<?php

use App\Http\Controllers\AppController;

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

Route::view('/', 'front-page');

Route::group(['prefix' => 'blog'], function() {
	Route::get('/', 'AppController@index')->name( 'posts' );
	Route::get('/{slug}', 'AppController@show')->name( 'single-post' );
});


// Route::group(['prefix' => 'dashboard'], function() {
//     Route::view('/', 'dashboard/dashboard');
//     Route::resource('posts', 'BlogController');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

