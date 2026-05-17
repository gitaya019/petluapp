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


        Route::get(

            'mascotas/{mascota}/historiales',

            [MascotaController::class, 'historiales']

        );

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
    });


/*Endpoint para enviar OTP*/

use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail;

Route::post('/otp/send', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $otp = rand(100000, 999999); // OTP de 6 dígitos
    $expiresAt = now()->addMinutes(10);

    UserOtp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => $expiresAt,
    ]);

    // Enviar OTP por correo
    Mail::raw("Tu código OTP es: $otp", function ($message) use ($user) {
        $message->to($user->email)
            ->subject('Código OTP de acceso');
    });

    return response()->json(['message' => 'OTP enviado']);
});


/*Endpoint para verificar OTP y generar token*/


Route::post('/otp/verify', function(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required|digits:6',
    ]);

    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    $otp = $user->otps()->latest()->first();

    if (!$otp || $otp->otp !== $request->otp || $otp->isExpired()) {
        return response()->json(['message' => 'OTP inválido o expirado'], 401);
    }

    // Eliminar OTP usado
    $otp->delete();

    // Generar token (usando sanctum o JWT)
    $token = $user->createToken('app-token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});
