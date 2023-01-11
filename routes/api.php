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

<<<<<<< HEAD
Route::post('/v0/user/signup', [Controllers\UserController::class, 'singup']);
Route::post('/v0/user/validate/email', [Controllers\UserController::class, 'validate_new_user']);
Route::get('/v0/user/login', [Controllers\UserController::class, 'login']);
Route::get('/v0/user/password/reset/validation', [Controllers\UserController::class, 'password_reset_validation']);
Route::post('/v0/user/password/reset', [Controllers\UserController::class, 'password_reset']);
=======
Route::prefix("/v0/user")->controller(Controllers\UserController::class)->group(function () {
	Route::get('/check/cuil', [Controllers\UserController::class, 'checkUserCuil']);
	Route::post('/signup', [Controllers\UserController::class, 'singup']);
	Route::post('/validate/email', [Controllers\UserController::class, 'validateNewUser']);
	Route::post('/login', [Controllers\UserController::class, 'login']);
	Route::get('/password/reset/validation', [Controllers\UserController::class, 'passwordResetValidation']);
	Route::post('/password/reset', [Controllers\UserController::class, 'passwordReset']);
    Route::middleware(['auth:authentication','scope:level_1'])->post('/personal/data', [Controllers\UserController::class,
	    'personalData'
    ]);
>>>>>>> e40bfe757f261588605a6116f2891d17defade28

});

<<<<<<< HEAD
Route::middleware(['auth:authentication','scope:nivel_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
=======
Route::middleware(['auth:authentication','scope:level_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
>>>>>>> e40bfe757f261588605a6116f2891d17defade28
