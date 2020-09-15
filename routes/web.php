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

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

Route::get('/redis', function () {
    if (Cache::store('redis')->has('token')){
        $value_cache=Cache::get('token');
    }
    else{
        $value_cache = Cache::store('redis')->put('token', 'MY_TOKE3E3E3E3E3E3E3E3N',600);
    }

    $visits = Redis::incr('visits');
    return [$visits,$value_cache];
});
Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');
