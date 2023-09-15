<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProcedureUnitController as Controller;

Route::prefix('/procedures')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::get('/', 'getList');
        Route::post('/', 'create');

        Route::get('/categories', 'getCategories');

        Route::get('/searchbyweb', 'getListBySearch'); //search procedures in expedintes system

        Route::get('/{id}', 'getById');

        Route::post('/update', 'updateByTitle');
        Route::post('/delete', 'deleteById');
    });
