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

});

Route::middleware(['auth:authentication','scope:level_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
