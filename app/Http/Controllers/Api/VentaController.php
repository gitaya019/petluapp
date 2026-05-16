<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Venta;

use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $ventas = Venta::where(
            'cliente_id',
            $request->user()->id
        )
        ->with([
            'clinica',
            'detalles',
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $ventas,
        ]);
    }

    public function show(Venta $venta)
    {
        return response()->json([
            'success' => true,
            'data' => $venta->load([
                'detalles',
                'clinica',
            ]),
        ]);
    }
}