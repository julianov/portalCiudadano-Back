<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/v0/user/singup', 'App\Http\Controllers\UserController@singup');
Route::post('/v0/user/validate/email', 'App\Http\Controllers\UserController@validate_new_user');
Route::get('/v0/user/login', 'App\Http\Controllers\UserController@login');


Route::middleware(['auth:authentication','scope:nivel_1'])->post('/v0/testroute', 'App\Http\Controllers\UserController@test');
