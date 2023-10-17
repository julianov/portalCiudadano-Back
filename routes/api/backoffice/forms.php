<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/forms')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');

        Route::get('/search', 'getListBySearch');
        Route::get('/published', 'getPublishedList');
        Route::get('/elements', 'getElementsByPk');

        Route::get('/getByPk', 'getByPk');

        Route::post('/update', 'updateByPk');
        Route::post('/delete', 'deleteByPk');
    });
