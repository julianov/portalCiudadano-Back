<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureUnitController as Controller;

Route::prefix('/procedures')
    ->controller(Controller::class)
    ->group(function () {
        Route::middleware(['auth:authentication'])->get('/', 'getList');
        Route::middleware(['auth:authentication'])->post('/', 'create');
        Route::middleware(['auth:authentication'])->post('/update', 'updateByTitle');
        Route::middleware(['auth:authentication'])->post('/delete', 'deleteByTitle');

        Route::middleware(['auth:authentication'])->get('/{id}', 'getByPk');
    });
