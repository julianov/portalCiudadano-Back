<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureUnitController as Controller;

Route::prefix('/procedures')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');

        Route::post('/update', 'updateByTitle');
        Route::post('/delete', 'deleteByTitle');

        Route::get('/{id}', 'getByPk');
    });
