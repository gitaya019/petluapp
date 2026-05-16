<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Cita;

use Illuminate\Http\Request;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $citas = Cita::whereHas(
            'mascota',
            fn ($q) =>
            $q->where(
                'user_id',
                $request->user()->id
            )
        )
        ->with([
            'mascota',
            'vacuna',
            'veterinario',
            'clinica',
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $citas,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'clinica_id' => [
                'required',
                'exists:clinicas,id'
            ],

            'mascota_id' => [
                'required',
                'exists:mascotas,id'
            ],

            'veterinario_id' => [
                'nullable',
                'exists:users,id'
            ],

            'vacuna_id' => [
                'nullable',
                'exists:vacunas,id'
            ],

            'fecha' => [
                'required',
                'date'
            ],

            'hora' => [
                'required'
            ],

            'motivo' => [
                'nullable',
                'string'
            ],

            'observaciones' => [
                'nullable',
                'string'
            ],
        ]);

        $cita = Cita::create($validated);

        return response()->json([
            'success' => true,
            'data' => $cita,
        ], 201);
    }

    public function show(Cita $cita)
    {
        return response()->json([
            'success' => true,
            'data' => $cita->load([
                'mascota',
                'vacuna',
                'clinica',
            ]),
        ]);
    }

    public function update(
        Request $request,
        Cita $cita
    ) {

        $validated = $request->validate([

            'estado' => [
                'required',
                'in:pendiente,confirmada,completada,cancelada,no_asistio'
            ],

            'observaciones' => [
                'nullable',
                'string'
            ],
        ]);

        $cita->update($validated);

        return response()->json([
            'success' => true,
            'data' => $cita,
        ]);
    }

    public function destroy(Cita $cita)
    {
        $cita->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}