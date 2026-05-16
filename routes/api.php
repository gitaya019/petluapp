<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;

/*
    |--------------------------------------------------------------------------
    | Autenticación
    |--------------------------------------------------------------------------
    */


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Mascotas
    |--------------------------------------------------------------------------
    */

    Route::get('/mascotas', [MascotaController::class, 'index']);

    Route::post('/mascotas', [MascotaController::class, 'store']);

    Route::get('/mascotas/{mascota}', [MascotaController::class, 'show']);

    Route::put('/mascotas/{mascota}', [MascotaController::class, 'update']);

    Route::delete('/mascotas/{mascota}', [MascotaController::class, 'destroy']);
});
