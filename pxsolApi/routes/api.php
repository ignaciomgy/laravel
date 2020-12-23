<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', 'UsersController');

Route::resource('userfiles', 'UserFilesController');

//upload file
Route::post('store_files', 'UserFilesController@store');

//list files from a user {user_id}
Route::get('auserfiles/{user_id}', 'UserFilesController@auserfiles');

//list all users with his files
Route::get('allusersandfiles', 'UserFilesController@allusersandfiles');
