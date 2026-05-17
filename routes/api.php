<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


use App\Models\User;
use App\Models\UserOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MascotaController;
use App\Http\Controllers\Api\CitaController;
use App\Http\Controllers\Api\RecordatorioController;
use App\Http\Controllers\Api\VentaController;
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


Route::post('/otp/send', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
    ]);

    // Buscar usuario
    $user = User::where('email', $request->email)->first();
    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Rate limit: 1 OTP cada 60 segundos
    $cacheKey = 'otp_send_' . $user->id;
    if (Cache::has($cacheKey)) {
        $seconds = Cache::get($cacheKey) - time();
        return response()->json([
            'message' => "Espera $seconds segundos antes de solicitar otro OTP"
        ], 429);
    }

    // Generar OTP de 6 dígitos y expiración
    $otp = rand(100000, 999999);
    $expiresAt = now()->addMinutes(10);

    UserOtp::create([
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => $expiresAt,
    ]);

    // Bloquear nuevo envío durante 60 segundos
    Cache::put($cacheKey, time() + 60, 60);

    // Enviar OTP por correo con HTML
    Mail::html("
        <div style='font-family: sans-serif; text-align:center; padding: 20px; background: #f4f4f4; border-radius: 12px;'>
            <h2 style='color: #6A4C93;'>¡Hola!</h2>
            <p style='font-size: 16px; color: #333;'>Tu código de acceso OTP para <strong>PetluApp</strong> es:</p>
            <div style='font-size: 32px; font-weight: bold; color: #2DB7A3; margin: 20px 0;'>$otp</div>
            <p style='font-size: 14px; color: #555;'>Este código expira en 10 minutos.</p>
            <hr style='margin:20px 0;'/>
            <p style='font-size:12px; color:#999;'>Si no solicitaste este código, ignora este correo.</p>
        </div>
    ", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('🚀 Código OTP de acceso PetluApp');
    });

    return response()->json(['message' => 'OTP enviado']);
});


/*Endpoint para verificar OTP y generar token*/


Route::post('/otp/verify', function (Request $request) {
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
