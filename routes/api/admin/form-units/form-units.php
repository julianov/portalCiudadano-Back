<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/form-units')
    ->controller(Controller::class)
    ->group(function () {
        Route::middleware(['auth:authentication'])->get('/', 'getList');
        Route::middleware(['auth:authentication'])->post('/', 'create');
        Route::middleware(['auth:authentication'])->post('/update', 'updateByPk');
        Route::middleware(['auth:authentication'])->post('/delete', 'deleteByPk');
    });
