<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)
    ->prefix('/v1')
    ->group(function () {
        Route::get('/me', 'me')->middleware('auth:sanctum');
        Route::post('/login', 'authenticate');
        Route::post('/logout', 'logout');
        Route::post('/register', 'register');
    });

Route::controller(PackageController::class)
    ->prefix('/v1/packages')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{package}', 'show');
        Route::delete('/{package}', 'destroy');
    });
