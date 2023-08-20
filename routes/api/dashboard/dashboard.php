<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/dashboard')
    ->group(function () {
        require __DIR__ . '/procedures.php';
        require __DIR__ . '/forms.php';
});
