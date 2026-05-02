<?php

namespace App\Filament\System\Widgets;

use App\Models\Clinica;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicasStats extends StatsOverviewWidget
{

    protected static ?int $sort = 1;

    protected function getStats(): array
    {

        // 🔝 KPIs globales (arriba)
        $kpis = [
            Stat::make('Clínicas', Clinica::count())
                ->icon('heroicon-o-building-office')
                ->color('primary'),

            Stat::make('Usuarios', \App\Models\User::count())
                ->icon('heroicon-o-users')
                ->color('info'),

            Stat::make('Mascotas', \App\Models\Mascota::count())
                ->icon('heroicon-o-heart')
                ->color('warning'),

            Stat::make('Vacunas aplicadas', \App\Models\VacunaAplicada::count())
                ->icon('heroicon-o-shield-check')
                ->color('success'),
        ];

        // 📦 Cargar clínicas con contadores optimizados (1 sola query)
        $clinicas = Clinica::query()
            ->withCount([
                'users',
                'mascotas',
                // 💉 mascotas con al menos 1 vacuna
                'mascotas as vacunadas_count' => fn($q) => $q->whereHas('vacunasAplicadas'),
                // ⚠️ mascotas sin vacunas
                'mascotas as no_vacunadas_count' => fn($q) => $q->whereDoesntHave('vacunasAplicadas'),
            ])
            ->get();

        // 🧩 Cards por clínica
        $cards = $clinicas->map(function ($c) {

            $color = $c->estado ? 'success' : 'danger';

            return Stat::make($c->nombre, $c->users_count . ' usuarios')
                ->description(
                    "🐶 {$c->mascotas_count} mascotas · 💉 {$c->vacunadas_count} vacunadas · ⚠️ {$c->no_vacunadas_count} sin vacunar"
                )
                ->icon('heroicon-o-building-storefront')
                ->color($color)
                ->extraAttributes([
                    'class' => 'rounded-xl shadow-md',
                ]);
        })->toArray();

        // Primero KPIs, luego cards por clínica
        return array_merge($kpis, $cards);
    }
}
