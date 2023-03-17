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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
	
});*/

Route::prefix("/v0/user")->controller(Controllers\UserController::class)->group(function () {
	Route::get('/check/cuil', [Controllers\UserController::class, 'checkUserCuil']);
	Route::post('/signup', [Controllers\UserController::class, 'singup']);
	Route::post('/validate/email', [Controllers\UserController::class, 'validateNewUser']);
	Route::post('/login', [Controllers\UserController::class, 'login']);
	Route::get('/password/reset/validation', [Controllers\UserController::class, 'passwordResetValidation']);
	Route::post('/password/reset', [Controllers\UserController::class, 'passwordReset']);
    Route::middleware(['auth:authentication'])->post('/contact/personal/data', [Controllers\UserController::class,'contactPersonalData']);
	Route::middleware(['auth:authentication'])->post('/personal/names', [Controllers\UserController::class,'personalNames']);
	Route::delete('/delete/user', [Controllers\UserController::class, 'eliminarUser']); //solo para testing.

	Route::get('/resend/email/verification', [Controllers\UserController::class, 'resendEmailVerificacion']);

	Route::middleware(['auth:authentication'])->get('/change/email/validation', [Controllers\UserController::class, 'changeNewEmailValidation']);
	Route::middleware(['auth:authentication'])->post('/change/email/', [Controllers\UserController::class, 'changeEmail']);


});


Route::prefix("/v0/authentication")->controller(Controllers\AuthController::class)->group(function () {

	Route::get('/afip/getUrl',[Controllers\AuthController::class, 'getUrlAfip']);
	Route::get('/afip/getToken', [Controllers\AuthController::class, 'getValidationAfip']);

	Route::get('/facetoface/user/GetData', [Controllers\AuthController::class, 'validateFaceToFaceGetData']);
	Route::post('/facetoface/user/Validate', [Controllers\AuthController::class, 'validateFaceToFaceCitizen']);

});

Route::prefix("/v0/er")->controller(Controllers\LocationsController::class)->group(function () {

	Route::middleware(['auth:authentication'])->get('/locations', [Controllers\LocationsController::class, 'getLocations']);
	Route::middleware(['auth:authentication'])->get('/getstringlocations', [Controllers\LocationsController::class, 'getStringLocations']);

});

Route::middleware(['auth:authentication','scope:level_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
