<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('/users/createUsers', 'UserController@createUser');
Route::post('/users/authenticate', 'UserController@loginAPI');
Route::get('/users/getAllUser/filter_value={firstname}&sort_order_mode={sort}&page_size={page}', 'UserController@getAllUser');
Route::get('/users/getUserById/{id}', 'UserController@getUserById');
Route::put('/users/editUser/{id}', 'UserController@editUser');
Route::delete('/users/deleteUser/{id}', 'UserController@deleteUser');