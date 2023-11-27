<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormDataController as Controller;

Route::prefix('/forms')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::post('/', 'create');
        Route::get('/', 'getList');
        Route::get('/getByPk', 'getByPk');

        Route::post('/update', 'updateById');

        Route::get('/elements', 'getElementsById');

        Route::post('/attachments', 'storeAttachments');
        Route::get('/attachments/', 'getAttachmentById');
        Route::get('/attachments/name/', 'getAttachmentName');
        Route::post('/attachments/delete', 'deleteAttachmentById');
    });
