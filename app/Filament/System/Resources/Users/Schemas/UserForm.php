<?php

namespace App\Filament\System\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información personal')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('telefono')
                            ->label('Teléfono'),

                        Toggle::make('estado')
                            ->label('Activo')
                            ->default(true),
                    ]),

                Section::make('Seguridad')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->required(fn ($record) => $record === null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->label('Contraseña'),

                        Toggle::make('is_super_admin')
                            ->label('Super Admin')
                            ->default(false),
                    ]),

                Section::make('Clínicas')
                    ->description('Clínicas a las que pertenece el usuario')
                    ->schema([
                        Select::make('clinicas')
                            ->relationship('clinicas', 'nombre')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }
}