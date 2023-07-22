<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureUnitController as Controller;

Route::prefix('/procedures')
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');
        Route::post('/update', 'updateByPk');
        Route::post('/delete', 'deleteByPk');

        Route::get('/{id}', 'getByPk');
    });
