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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login')->name('api.auth');
    Route::post('logout', 'AuthController@logout')->name('api.logout');
    Route::post('refresh', 'AuthController@refresh')->name('api.refresh');
    Route::post('register', 'AuthController@register')->name('api.reg');
    Route::post('user', 'AuthController@user')->name('api.refresh');
});
Route::get('/users', 'UserController@home');
