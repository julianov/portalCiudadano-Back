<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/admin')
    ->group(function () {
        require __DIR__ . '/form-units/form-units.php';
        require __DIR__ . '/procedure-units/procedure-units.php';
});
