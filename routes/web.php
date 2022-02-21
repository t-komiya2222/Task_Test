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

#Posts Route
Route::resource('/post', 'PostController');

#Posts Download Route
Route::post('/download', 'PostController@download')->name('download');

#Likes Route
Route::post('/addlike', 'LikeController@addlike')->name('addlike');
Route::post('/dislike', 'LikeController@dislike')->name('dislike');
Route::get('/alllike', 'LikeController@alllike')->name('alllike');
