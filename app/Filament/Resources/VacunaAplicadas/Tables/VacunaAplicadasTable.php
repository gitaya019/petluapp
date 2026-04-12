<?php

namespace App\Filament\Resources\VacunaAplicadas\Tables;

use App\Models\VacunaAplicada;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VacunaAplicadasTable
{
    public static function configure(Table $table): Table
    {
        return $table

            // 🔥 AGRUPAR POR MASCOTA + VACUNA
            ->modifyQueryUsing(function ($query) {
                return $query
                    ->selectRaw('MIN(id) as id, mascota_id, vacuna_id')
                    ->groupBy('mascota_id', 'vacuna_id')
                    ->orderBy('id'); // 👈 ahora sí válido
            })
            ->columns([
                TextColumn::make('mascota.nombre')
                    ->label('Mascota'),

                TextColumn::make('vacuna.nombre')
                    ->label('Vacuna'),

                // 🟢 DOSIS
                TextColumn::make('dosis')
                    ->label('Dosis')
                    ->getStateUsing(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        return "{$aplicadas} / {$total}";
                    })
                    ->badge()
                    ->color(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        return $aplicadas >= $total ? 'success' : 'warning';
                    }),

                // 🟡 ÚLTIMA DOSIS
                TextColumn::make('ultima_dosis')
                    ->label('Última aplicación')
                    ->getStateUsing(function ($record) {

                        $ultima = VacunaAplicada::ultimaAplicacion(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        return $ultima?->fecha_aplicacion?->format('Y-m-d') ?? 'Sin aplicar';
                    }),

                // 🔴 PRÓXIMA DOSIS
                TextColumn::make('proxima_dosis')
                    ->label('Próxima dosis')
                    ->getStateUsing(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        // ✅ SI YA COMPLETÓ
                        if ($aplicadas >= $total) {
                            return 'Completado';
                        }

                        $fecha = VacunaAplicada::proximaDosis(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        return $fecha?->format('Y-m-d');
                    })
                    ->badge()
                    ->color(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        // 🟢 COMPLETADO
                        if ($aplicadas >= $total) {
                            return 'success';
                        }

                        $fecha = VacunaAplicada::proximaDosis(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        // 🔴 VENCIDA
                        if (now()->greaterThanOrEqualTo($fecha)) {
                            return 'danger';
                        }

                        // 🟡 PRÓXIMA
                        return 'warning';
                    })
            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make(),

                Action::make('aplicar_dosis')
                    ->label(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        return ($aplicadas + 1 >= $total)
                            ? 'Aplicar última dosis'
                            : 'Aplicar siguiente dosis';
                    })
                    ->icon('heroicon-o-check-badge')
                    ->color(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        return ($aplicadas + 1 >= $total)
                            ? 'success'
                            : 'warning';
                    })
                    ->visible(function ($record) {

                        $aplicadas = VacunaAplicada::totalAplicadas(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $total = $record->vacuna->dosis ?? 1;

                        return $aplicadas < $total;
                    })
                    ->requiresConfirmation()
                    ->action(function ($record) {

                        $user = Auth::user();

                        $ultima = VacunaAplicada::ultimaAplicacion(
                            $record->mascota_id,
                            $record->vacuna_id
                        );

                        $lote = $ultima?->lote;

                        if (!$lote || $lote->stock_actual <= 0) {
                            throw new \Exception('No hay stock disponible');
                        }

                        VacunaAplicada::create([
                            'clinica_id' => $ultima->clinica_id,
                            'mascota_id' => $record->mascota_id,
                            'vacuna_id' => $record->vacuna_id,
                            'lote_id' => $lote->id,
                            'veterinario_id' => $user?->id,
                            'fecha_aplicacion' => now(),
                        ]);
                    }),

                Action::make('ver_historial')
                    ->label('Historial')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading('Historial de dosis')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(function ($record) {

                        $historial = VacunaAplicada::where('mascota_id', $record->mascota_id)
                            ->where('vacuna_id', $record->vacuna_id)
                            ->orderBy('fecha_aplicacion')
                            ->get();

                        $html = '<div style="display:flex;flex-direction:column;gap:10px;">';

                        foreach ($historial as $index => $item) {
                            $numero = $index + 1;
                            $fecha = \Carbon\Carbon::parse($item->fecha_aplicacion)->format('Y-m-d');

                            $html .= "
                <div style='padding:10px;border:1px solid #ddd;border-radius:8px'>
                    <strong>Dosis {$numero}</strong><br>
                    Fecha: {$fecha}
                </div>
            ";
                        }

                        $html .= '</div>';

                        return new \Illuminate\Support\HtmlString($html);
                    }),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
