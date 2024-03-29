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
	Route::post('/change/email/level1', [Controllers\UserController::class, 'changeEmailLevel1']);

	Route::post('/logout', [Controllers\UserController::class, 'logout']);

});


Route::prefix("/v0/authentication")->controller(Controllers\AuthController::class)->group(function () {

	Route::middleware(['auth:authentication'])->get('/afip/getUrl',[Controllers\AuthController::class, 'getUrlAfip']);
	Route::middleware(['auth:authentication'])->get('/afip/getToken', [Controllers\AuthController::class, 'getValidationAfip']);

	Route::middleware(['auth:authentication'])->get('/miargentina/getUrl',[Controllers\AuthController::class, 'getUrlMiArgentina']);
	Route::middleware(['auth:authentication'])->get('/miargentina/getToken', [Controllers\AuthController::class, 'getValidationMiArgentina']);

	Route::middleware(['auth:authentication'])->get('/anses/getUrl',[Controllers\AuthController::class, 'getUrlAnses']);
	Route::middleware(['auth:authentication'])->get('/anses/getToken', [Controllers\AuthController::class, 'getValidationAnses']);

	Route::middleware(['auth:authentication'])->get('/renaper/getUrl',[Controllers\AuthController::class, 'getUrlRenaper']);
	Route::middleware(['auth:authentication'])->get('/renaper/getToken', [Controllers\AuthController::class, 'getValidationRenaper']);

	Route::get('/facetoface/user/GetData', [Controllers\AuthController::class, 'validateFaceToFaceGetData']);
	Route::post('/facetoface/user/Validate', [Controllers\AuthController::class, 'validateFaceToFaceCitizen']);

	Route::get('/actor/redirect', [Controllers\AuthController::class, 'ActorRedirect']);

});

Route::prefix("/v0/er")->controller(Controllers\LocationsController::class)->group(function () {

	Route::get('/locations', [Controllers\LocationsController::class, 'getLocations']);
	Route::get('/getstringlocations', [Controllers\LocationsController::class, 'getStringLocations']);

});


Route::prefix("/v0/notification")->controller(Controllers\NotificationsController::class)->group(function () {

	Route::middleware(['auth:authentication'])->post('/new', [Controllers\NotificationsController::class, 'newNotification']);
	Route::middleware(['auth:authentication'])->get('/get/user/news', [Controllers\NotificationsController::class, 'checkUserNewNotifications']);
	Route::middleware(['auth:authentication'])->get('/get/user/all', [Controllers\NotificationsController::class, 'checkUserAllNotifications']);
	Route::middleware(['auth:authentication'])->get('/get/user/attachments', [Controllers\NotificationsController::class, 'getNotificationsAttachments']);
	Route::middleware(['auth:authentication'])->post('/get/user/read', [Controllers\NotificationsController::class, 'userNotificationRead']);
	Route::middleware(['auth:authentication'])->get('/get/actor/active/news', [Controllers\NotificationsController::class, 'checkAllNotifications']);
	Route::middleware(['auth:authentication'])->get('/get/attachment/name', [Controllers\NotificationsController::class, 'getNotificationAttachmentName']);
	Route::middleware(['auth:authentication'])->get('/new/scope', [Controllers\NotificationsController::class, 'checkNotificationScope']);

	Route::middleware(['auth:authentication'])->post('/attachment/delete', [Controllers\NotificationsController::class, 'deleteNotificationsAttachments']);
	Route::middleware(['auth:authentication'])->post('/notification/soft-delete', [Controllers\NotificationsController::class, 'deleteNotification']);
	Route::middleware(['auth:authentication'])->get('/notification/number-reached', [Controllers\NotificationsController::class, 'notificationReached']);

});

Route::prefix("/v0/metrics")->controller(Controllers\MetricasController::class)->group(function () {
	Route::middleware(['auth:authentication'])->get('/all', [Controllers\MetricasController::class, 'metrics']);

}); 

// Route modularization
Route::prefix('/v0')
    ->group(function () {
        require __DIR__ . '/api/dashboard/dashboard.php';
        require __DIR__ . '/api/backoffice/backoffice.php';
});
