<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/form-units')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');
        Route::post('/update', 'updateByPk');
        Route::post('/delete', 'deleteByPk');
    });
