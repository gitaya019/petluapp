<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Recordatorio;

use Illuminate\Http\Request;

class RecordatorioController extends Controller
{
    public function index(Request $request)
    {
        $recordatorios = Recordatorio::whereHas(
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
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $recordatorios,
        ]);
    }

    public function show(
        Recordatorio $recordatorio
    ) {

        return response()->json([
            'success' => true,
            'data' => $recordatorio,
        ]);
    }

    public function destroy(
        Recordatorio $recordatorio
    ) {

        $recordatorio->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}