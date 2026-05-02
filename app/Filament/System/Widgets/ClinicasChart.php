<?php

namespace App\Filament\System\Widgets;

use App\Models\Clinica;
use Filament\Widgets\ChartWidget;

class ClinicasChart extends ChartWidget
{
    protected ?string $heading = 'Mascotas por Clínica';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Clinica::withCount([
            'mascotas',
            'mascotas as vacunadas_count' => fn($q) => $q->whereHas('vacunasAplicadas')
        ])->get();

        return [
            'datasets' => [
                [
                    'label' => 'Mascotas',
                    'data' => $data->pluck('mascotas_count'),
                ],
                [
                    'label' => 'Vacunadas',
                    'data' => $data->pluck('vacunadas_count'),
                ],
            ],
            'labels' => $data->pluck('nombre'),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

}
