<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            return response()->json([
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        $user = Auth::user();

        // Crear token Sanctum
        $token = $user
            ->createToken('react-app')
            ->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    }
}