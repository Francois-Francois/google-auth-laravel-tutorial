<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');


Route::get('/2fa/enable', 'GoogleAuthController@enableTwoFactor');
Route::get('/2fa/disable', 'GoogleAuthController@disableTwoFactor');
Route::post('/2fa/activate', 'GoogleAuthController@activateTwoFactor');

Route::get('/2fa/validate', 'Auth\LoginController@validate2fa');
Route::post('/2fa/validate', ['middleware' => 'throttle:5', 'uses' => 'Auth\LoginController@postValidate2fa']);