<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\FormDataController as Controller;

Route::prefix('/forms')
    ->middleware(['auth:authentication'])
    ->controller(Controller::class)
    ->group(function () {
        Route::post('/', 'create');
    });
