<?php

namespace App\Filament\System\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

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
                            ->label('Contraseña generada')
                            ->default(fn() => Str::random(10))
                            ->password(false)
                            ->readOnly()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->visible(fn($record) => $record === null)
                            ->helperText('Copia esta contraseña y compártela con el usuario'),
                        Toggle::make('is_super_admin')
                            ->label('Super Admin')
                            ->default(false),
                    ]),

                Section::make('Clínicas y roles')
                    ->description('Asigna roles del usuario por clínica')
                    ->schema([
                        \Filament\Forms\Components\Repeater::make('clinica_roles')
                            ->label('Asignaciones')
                            ->schema([
                                Select::make('clinica_id')
                                    ->label('Clínica')
                                    ->relationship('clinicas', 'nombre')
                                    ->placeholder('Selecciona una clinica')
                                    ->loadingMessage('Cargando clínicas...')
                                    ->noSearchResultsMessage('No se encontraron clínicas')
                                    ->noOptionsMessage('No hay clínicas disponibles')
                                    ->searchingMessage('buscando clínicas...')
                                    ->searchDebounce(500)
                                    ->searchPrompt('Buscar por nombre...')
                                    ->required()
                                    ->reactive(),

                                Select::make('role_id')
                                    ->label('Rol')
                                    ->placeholder('Selecciona un rol')
                                    ->loadingMessage('Cargando roles...')
                                    ->noSearchResultsMessage('No se encontraron roles')
                                    ->noOptionsMessage('No hay roles disponibles')
                                    ->searchingMessage('buscando roles...')
                                    ->searchDebounce(500)
                                    ->searchPrompt('Buscar por nombre...')
                                    ->options(function (callable $get) {
                                        $clinicaId = $get('clinica_id');

                                        return \App\Models\Role::query()
                                            ->when($clinicaId, fn($q) => $q->where('clinica_id', $clinicaId))
                                            ->pluck('name', 'id');
                                    })
                                    ->required(),
                            ])
                            ->columns(2)
                            ->createItemButtonLabel('Agregar clínica + rol'),
                    ]),
            ]);
    }
}
