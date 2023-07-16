<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/form-units')
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');
        Route::post('/update', 'updateByPk');
        Route::post('/delete', 'deleteByPk');


       /* Route::prefix('/')->group(function () {
            Route::get('/', 'getByPk');
            Route::post('/', 'updateByPk');
            Route::delete('/', 'deleteByPk');
        });*/
    });
