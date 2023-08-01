<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/form-units')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::middleware(['auth:authentication'])->get('/', 'getList');
        Route::middleware(['auth:authentication'])->get('/published', 'getPublishedList');
        Route::middleware(['auth:authentication'])->post('/', 'create');
        Route::middleware(['auth:authentication'])->post('/update', 'updateByPk');
        Route::middleware(['auth:authentication'])->post('/delete', 'deleteByPk');
        Route::middleware(['auth:authentication'])->get('/getByPk', 'getByPk');

    });
