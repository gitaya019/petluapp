<?php

namespace App\Filament\Resources\LoteVacunas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;

class LoteVacunaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lote de Vacuna')
                    ->icon('heroicon-o-archive-box')
                    ->schema([
                        Select::make('vacuna_id')
                            ->relationship('vacuna', 'nombre')
                            ->searchable()
                            ->required(),

                        TextInput::make('numero_lote')
                            ->required(),

                        DatePicker::make('fecha_vencimiento'),

                        TextInput::make('stock_inicial')
                            ->numeric()
                            ->required(),

                        TextInput::make('stock_actual')
                            ->numeric()
                            ->required(),
                    ])->columns(2)
            ]);
    }
}
