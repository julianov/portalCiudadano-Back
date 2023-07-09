<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormUnitController as Controller;

Route::prefix('/form-units')->controller(Controller::class)->group(
    function () {
        Route::get('/', 'getList');

        Route::post('/', 'create');

//         Route::prefix('/{code}/{version}')->group(function () {
//             Route::get('/', 'getByPk');
//
//             Route::put('/', 'updateByPk');
//
//             Route::delete('/', 'deleteByPk');
//
//             // Route::delete('/remove', 'removeByPk');
//         });
    }
);
