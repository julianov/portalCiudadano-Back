<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\DB;
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
Route::get("/test", function () {
    $users = DB::table("docker")->get();
    return $users;
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/v0/user/check/cuil', [Controllers\UserController::class, 'checkUserCuil']);
Route::post('/v0/user/signup', [Controllers\UserController::class, 'singup']);
Route::post('/v0/user/validate/email', [Controllers\UserController::class, 'validate_new_user']);
Route::get('/v0/user/login', [Controllers\UserController::class, 'login']);
Route::get('/v0/user/password/reset/validation', [Controllers\UserController::class, 'password_reset_validation']);
Route::post('/v0/user/password/reset', [Controllers\UserController::class, 'password_reset']);


Route::middleware(['auth:authentication','scope:nivel_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
