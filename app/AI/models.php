<?php

return [

    /**
     * CITAS
     */

    'citas' => [

        'model' => \App\Models\Cita::class,

        'searchable' => [
            'estado',
            'motivo',
            'observaciones',
            'fecha',
        ],

        'relations' => [

            'clinica',
            'mascota',
            'veterinario',
            'vacuna',

        ],

        'description' =>
            'Citas veterinarias de mascotas',

    ],

    /**
     * CLINICAS
     */

    'clinicas' => [

        'model' => \App\Models\Clinica::class,

        'searchable' => [
            'nombre',
            'nit',
            'direccion',
            'telefono',
            'email',
        ],

        'relations' => [

            'mascotas',
            'vacunas',
            'citas',

        ],

        'description' =>
            'Clínicas veterinarias registradas',

    ],

    /**
     * DETALLE VENTAS
     */

    'detalle_ventas' => [

        'model' => \App\Models\DetalleVenta::class,

        'searchable' => [
            'tipo_item',
            'descripcion',
        ],

        'relations' => [

            'venta',

        ],

        'description' =>
            'Productos y servicios vendidos',

    ],

    /**
     * HISTORIALES MEDICOS
     */

    'historiales_medicos' => [

        'model' => \App\Models\HistorialMedico::class,

        'searchable' => [
            'motivo_consulta',
            'diagnostico',
            'tratamiento',
            'observaciones',
            'fecha',
        ],

        'relations' => [

            'clinica',
            'mascota',
            'veterinario',

        ],

        'description' =>
            'Historial médico veterinario',

    ],

    /**
     * LOTES VACUNAS
     */

    'lote_vacunas' => [

        'model' => \App\Models\LoteVacuna::class,

        'searchable' => [
            'numero_lote',
        ],

        'relations' => [

            'clinica',
            'vacuna',

        ],

        'description' =>
            'Inventario de lotes de vacunas',

    ],

    /**
     * MASCOTAS
     */

    'mascotas' => [

        'model' => \App\Models\Mascota::class,

        'searchable' => [
            'nombre',
            'especie',
            'raza',
            'sexo',
            'color',
        ],

        'relations' => [

            'clinica',
            'user',
            'vacunasAplicadas',
            'historialesMedicos',
            'recordatorios',
            'citas',

        ],

        'description' =>
            'Mascotas registradas en el sistema',

    ],

    /**
     * MOVIMIENTO STOCKS
     */

    'movimiento_stocks' => [

        'model' => \App\Models\MovimientoStock::class,

        'searchable' => [
            'tipo',
            'motivo',
            'fecha',
        ],

        'relations' => [

            'clinica',
            'lote',

        ],

        'description' =>
            'Movimientos de inventario de vacunas',

    ],

    /**
     * RECORDATORIOS
     */

    'recordatorios' => [

        'model' => \App\Models\Recordatorio::class,

        'searchable' => [
            'tipo',
            'mensaje',
            'estado',
            'correo_destino',
        ],

        'relations' => [

            'clinica',
            'mascota',
            'vacuna',
            'vacunaAplicada',

        ],

        'description' =>
            'Recordatorios automáticos',

    ],

    /**
     * USUARIOS
     */

    'usuarios' => [

        'model' => \App\Models\User::class,

        'searchable' => [
            'name',
            'email',
            'numero_documento',
            'telefono',
        ],

        'relations' => [

            'mascotas',
            'ventas',

        ],

        'description' =>
            'Clientes y veterinarios del sistema',

    ],

    /**
     * VACUNAS
     */

    'vacunas' => [

        'model' => \App\Models\Vacuna::class,

        'searchable' => [
            'nombre',
            'descripcion',
            'fabricante',
        ],

        'relations' => [

            'clinica',
            'lotes',
            'vacunasAplicadas',

        ],

        'description' =>
            'Vacunas disponibles',

    ],

    /**
     * VACUNAS APLICADAS
     */

    'vacunas_aplicadas' => [

        'model' => \App\Models\VacunaAplicada::class,

        'searchable' => [
            'fecha_aplicacion',
            'observaciones',
        ],

        'relations' => [

            'clinica',
            'mascota',
            'vacuna',
            'lote',
            'veterinario',

        ],

        'description' =>
            'Vacunas aplicadas a mascotas',

    ],

    /**
     * VENTAS
     */

    'ventas' => [

        'model' => \App\Models\Venta::class,

        'searchable' => [
            'estado',
            'total',
        ],

        'relations' => [

            'clinica',
            'usuario',
            'cliente',
            'detalles',

        ],

        'description' =>
            'Ventas realizadas',

    ],

];