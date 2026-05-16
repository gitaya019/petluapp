<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listar mascotas del usuario autenticado
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $mascotas = Mascota::where(  //ignore this intelephense error
            'user_id',
            $request->user()->id
        )
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $mascotas,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Crear mascota
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'clinica_id' => ['required', 'exists:clinicas,id'],

            'nombre' => ['required', 'string', 'max:255'],

            'especie' => ['required', 'string', 'max:255'],

            'raza' => ['nullable', 'string', 'max:255'],

            'fecha_nacimiento' => ['nullable', 'date'],

            'sexo' => ['nullable', 'string', 'max:50'],

            'peso' => ['nullable', 'numeric'],

            'color' => ['nullable', 'string', 'max:255'],
        ]);

        $mascota = Mascota::create([

            ...$validated,

            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mascota creada correctamente',
            'data' => $mascota,
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Ver mascota
    |--------------------------------------------------------------------------
    */

    public function show(Request $request, Mascota $mascota)
    {
        if ($mascota->user_id !== $request->user()->id) {

            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $mascota,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar mascota
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Mascota $mascota)
    {
        if ($mascota->user_id !== $request->user()->id) {

            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 403);
        }

        $validated = $request->validate([

            'clinica_id' => ['required', 'exists:clinicas,id'],

            'nombre' => ['required', 'string', 'max:255'],

            'especie' => ['required', 'string', 'max:255'],

            'raza' => ['nullable', 'string', 'max:255'],

            'fecha_nacimiento' => ['nullable', 'date'],

            'sexo' => ['nullable', 'string', 'max:50'],

            'peso' => ['nullable', 'numeric'],

            'color' => ['nullable', 'string', 'max:255'],
        ]);

        $mascota->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Mascota actualizada correctamente',
            'data' => $mascota,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar mascota
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, Mascota $mascota)
    {
        if ($mascota->user_id !== $request->user()->id) {

            return response()->json([
                'success' => false,
                'message' => 'No autorizado',
            ], 403);
        }

        $mascota->delete(); //ignore this intelephense error, el modelo usa SoftDeletes

        return response()->json([
            'success' => true,
            'message' => 'Mascota eliminada correctamente',
        ]);
    }
}