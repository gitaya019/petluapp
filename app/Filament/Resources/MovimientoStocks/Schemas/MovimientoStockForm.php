<?php

namespace App\Filament\Resources\MovimientoStocks\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;

class MovimientoStockForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Movimiento de Stock')
                    ->icon('heroicon-o-arrows-right-left')
                    ->schema([
                        Select::make('lote_id')
                            ->relationship('lote', 'numero_lote')
                            ->required(),

                        Select::make('tipo')
                            ->options([
                                'entrada' => 'Entrada',
                                'salida' => 'Salida',
                            ])
                            ->required(),

                        TextInput::make('cantidad')
                            ->numeric()
                            ->required(),

                        TextInput::make('motivo'),

                        DatePicker::make('fecha')
                            ->required(),
                    ])->columns(2)
            ]);
    }
}
