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
    Route::middleware(['auth:authentication'])->post('/personal/contact/data', [Controllers\UserController::class,'contactPersonalData']);
	Route::middleware(['auth:authentication'])->post('/personal/names', [Controllers\UserController::class,'personalNames']);
	Route::delete('/delete/user', [Controllers\UserController::class, 'eliminarUser']); //solo para testing.

	Route::get('/resend/email/verification', [Controllers\UserController::class, 'resendEmailVerificacion']);

	Route::middleware(['auth:authentication'])->get('/change/email/validation', [Controllers\UserController::class, 'changeNewEmailValidation']);
	Route::middleware(['auth:authentication'])->post('/change/email/', [Controllers\UserController::class, 'changeEmail']);


});


Route::prefix("/v0/authentication")->controller(Controllers\AuthController::class)->group(function () {

	Route::middleware(['auth:authentication'])->get('/afip/getUrl',[Controllers\AuthController::class, 'getUrlAfip']);
	Route::middleware(['auth:authentication'])->get('/afip/getToken', [Controllers\AuthController::class, 'getValidationAfip']);

	Route::middleware(['auth:authentication'])->get('/miargentina/getUrl',[Controllers\AuthController::class, 'getUrlMiArgentina']);
	Route::middleware(['auth:authentication'])->get('/miargentina/getToken', [Controllers\AuthController::class, 'getValidationMiArgentina']);

	Route::get('/facetoface/user/GetData', [Controllers\AuthController::class, 'validateFaceToFaceGetData']);
	Route::post('/facetoface/user/Validate', [Controllers\AuthController::class, 'validateFaceToFaceCitizen']);

	Route::get('/actor/redirect', [Controllers\AuthController::class, 'ActorRedirect']);

});

Route::prefix("/v0/er")->controller(Controllers\LocationsController::class)->group(function () {

	Route::get('/locations', [Controllers\LocationsController::class, 'getLocations']);
	Route::get('/getstringlocations', [Controllers\LocationsController::class, 'getStringLocations']);

});


Route::prefix("/v0/notification")->controller(Controllers\NotificationsController::class)->group(function () {

	Route::post('/new', [Controllers\NotificationsController::class, 'newNotification']);
	Route::middleware(['auth:authentication'])->get('/get/user/news', [Controllers\NotificationsController::class, 'checkUserNotifications']);
	Route::middleware(['auth:authentication'])->get('/get/user/attachments', [Controllers\NotificationsController::class, 'getNotificationsAttachments']);
	Route::middleware(['auth:authentication'])->post('/get/user/read', [Controllers\NotificationsController::class, 'userNotificationRead']);
	Route::middleware(['auth:authentication'])->get('/get/actor/active/news', [Controllers\NotificationsController::class, 'checkAllNotifications']);
});



Route::middleware(['auth:authentication','scope:level_1'])->post('/v0/testroute', [Controllers\UserController::class, 'test']);
