<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/backoffice')
    ->group(function () {
        require __DIR__ . '/procedures.php';
});
