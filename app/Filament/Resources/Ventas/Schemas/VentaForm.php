<?php

namespace App\Filament\Resources\Ventas\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class VentaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Venta')
    ->icon('heroicon-o-currency-dollar')
    ->schema([
        Select::make('usuario_id')
            ->relationship('usuario', 'name')
            ->required(),

        Select::make('cliente_id')
            ->relationship('cliente', 'name')
            ->required(),

        TextInput::make('total')
            ->numeric()
            ->required(),

        Select::make('estado')
            ->options([
                'pagado' => 'Pagado',
                'pendiente' => 'Pendiente',
            ])
            ->required(),

        DatePicker::make('fecha')
            ->required(),
    ])->columns(2)
            ]);
    }
}
