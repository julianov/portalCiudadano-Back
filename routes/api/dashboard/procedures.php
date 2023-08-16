<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureDataController as Controller;

Route::prefix('/procedures')
    // ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');
        Route::get('/published', 'getListAvailable');
        Route::get('/getById', 'getById');
        Route::post('/update', 'updateById');
        Route::post('/delete', 'deleteById');

        Route::controller(FormDataController::class)->prefix('/forms');
        Route::post('/forms', 'createForm');
        Route::post('/forms/update', 'updateForm');
    });
