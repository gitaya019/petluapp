<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\RecordatorioController;
use App\Http\Controllers\Api\VentaController;
use App\Http\Controllers\Api\HistorialMedicoController;

    /*
    |--------------------------------------------------------------------------
    | Autenticación
    |--------------------------------------------------------------------------
    */

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);

    /*
    |--------------------------------------------------------------------------
    | Rutas protegidas
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')
        ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Usuario autenticado
    |--------------------------------------------------------------------------
    */

        Route::get(
            '/me',
            function (Request $request) {

                return response()->json([
                    'user' => $request
                        ->user()
                        ->load('clinicas'),
                ]);
            }
        );

        /*
    |--------------------------------------------------------------------------
    | Mascotas
    |--------------------------------------------------------------------------
    */

        Route::get('/mascotas', [
            MascotaController::class,
            'index',
        ]);

        Route::get('/mascotas/{mascota}', [
            MascotaController::class,
            'show',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Citas
    |--------------------------------------------------------------------------
    */

        Route::get('/citas', [
            CitaController::class,
            'index',
        ]);

        Route::get('/citas/{cita}', [
            CitaController::class,
            'show',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Recordatorios
    |--------------------------------------------------------------------------
    */

        Route::get('/recordatorios', [
            RecordatorioController::class,
            'index',
        ]);

        Route::get('/recordatorios/{recordatorio}', [
            RecordatorioController::class,
            'show',
        ]);

        /*
    |--------------------------------------------------------------------------
    | Compras (Ventas)
    |--------------------------------------------------------------------------
    */

        Route::get('/ventas', [
            VentaController::class,
            'index',
        ]);

        Route::get('/ventas/{venta}', [
            VentaController::class,
            'show',
        ]);

    /*
    |--------------------------------------------------------------------------
    | Historiales Médicos
    |--------------------------------------------------------------------------
    */

    Route::get('/historiales-medicos', [
        HistorialMedicoController::class,
        'index',
    ]);

    Route::get('/historiales-medicos/{historialMedico}', [
        HistorialMedicoController::class,
        'show',
    ]);
    });
