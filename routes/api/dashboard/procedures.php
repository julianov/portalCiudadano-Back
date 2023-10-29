<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureDataController as Controller;

Route::prefix('/procedures')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');
        Route::get('/getById', 'getById');
        Route::get('/getByProcedureUnitId', 'getByProcedureUnitId');

        Route::post('/update', 'updateById');
        Route::post('/delete', 'deleteById');

        Route::get('/published', 'getListAvailable');
        Route::get('/search', 'getListBySearch');

        Route::post('/attachments', 'storeAttachments');
        Route::get('/attachments/', 'getAttachmentById');
        Route::get('/attachments/name/', 'getAttachmentName');

        Route::post('/attachments/delete', 'deleteAttachmentById');
    });

