<?php

use Illuminate\Http\Request;

Route::middleware(['jwt.verify'])->group(function(){
    //api
    Route::post('tambah', 'ProjectController@store');
    Route::get('project', 'ProjectController@getAll');
    Route::post('project/{id}', 'ProjectController@update');
    Route::delete('project/{id}', 'ProjectController@destroy');
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');

// Route::middleware(['jwt.verify'])->group(function(){
// 	Route::get('book', 'BookController@book');
// 	Route::get('bookall', 'BookController@bookAuth');
// 	Route::get('user', 'UserController@getAuthenticatedUser');
// });

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

