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
    Route::middleware(['auth:authentication'])->post('/personal/data', [Controllers\UserController::class,'personalData']);
	Route::delete('/delete/user', [Controllers\UserController::class, 'eliminarUser']); //solo para testing.

	Route::get('/resend/email/verification', [Controllers\UserController::class, 'resendEmailVerificacion']);

	Route::middleware(['auth:authentication'])->get('/change/email/validation', [Controllers\UserController::class, 'changeNewEmailValidation']);
	Route::middleware(['auth:authentication'])->post('/change/email/', [Controllers\UserController::class, 'changeEmail']);


});


Route::middleware(['auth:authentication'])->get('/v0/er/locations', [Controllers\LocationsController::class, 'getLocations']);
Route::middleware(['auth:authentication'])->get('/v0/er/getstringlocations', [Controllers\LocationsController::class, 'getStringLocations']);

Route::get('/v0/getAfipUrl',[Controllers\AuthController::class, 'getUrlAfip']);
Route::get('/v0/getTokenAfip/', [Controllers\AuthController::class, 'getValidationAfip']);


Route::post('/v0/validate/face-to-face/citizen/', [Controllers\AuthController::class, 'validateFaceToFaceCitizen']);


Route::middleware(['auth:authentication','scope:level_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
