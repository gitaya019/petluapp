<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\HistorialMedico;

use Illuminate\Http\Request;

class HistorialMedicoController
    extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listar historiales médicos
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $historiales =
            HistorialMedico::query()

            ->whereHas(
                'mascota',
                function ($query)
                use ($request) {

                    $query->where(
                        'user_id',
                        $request->user()->id
                    );
                }
            )

            ->with([

                'mascota:id,nombre',

                'veterinario:id,name',

                'clinica:id,nombre',
            ])

            ->latest()

            ->get();

        return response()->json([

            'success' => true,

            'data' => $historiales,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Ver historial médico
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        HistorialMedico $historialMedico
    ) {

        if (
            $historialMedico
                ->mascota
                ->user_id !==
            $request->user()->id
        ) {

            return response()->json([

                'success' => false,

                'message' =>
                    'No autorizado',
            ], 403);
        }

        $historialMedico->load([

            'mascota:id,nombre',

            'veterinario:id,name',

            'clinica:id,nombre',
        ]);

        return response()->json([

            'success' => true,

            'data' => $historialMedico,
        ]);
    }
}