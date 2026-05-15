<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del Usuario')
                ->icon('heroicon-o-user')
                ->schema([
                    TextInput::make('name')
                        ->required(),

                    TextInput::make('email')
                        ->email()
                        ->required(),

                    // 🔐 CONTRASEÑA ALEATORIA
                    TextInput::make('password')
                        ->label('Contraseña generada')
                        ->default(fn() => Str::random(10))
                        ->password(false)
                        ->readOnly()
                        ->dehydrateStateUsing(fn($state) => Hash::make($state))
                        ->visible(fn($record) => $record === null)
                        ->helperText('Copia esta contraseña y compártela con el usuario'),

                    TextInput::make('numero_documento'),

                    Select::make('tipo_documento')
                        ->placeholder('Selecciona un tipo de documento')
                        ->loadingMessage('Cargando tipos de documento...')
                        ->noOptionsMessage('No hay tipos de documento disponibles')
                        ->options([
                            'CC' => 'Cédula',
                            'TI' => 'Tarjeta',
                        ])
                        ->default('CC'),

                    TextInput::make('telefono'),

                    Toggle::make('estado')
                        ->default(true),

                    Select::make('roles')
                        ->label('Roles')
                        ->placeholder('Selecciona un rol')
                        ->loadingMessage('Cargando roles...')
                        ->noSearchResultsMessage('No se encontraron roles')
                        ->noOptionsMessage('No hay roles disponibles')
                        ->searchingMessage('buscando roles...')
                        ->searchDebounce(500)
                        ->searchPrompt('Buscar por nombre...')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) =>
                                $query->where('roles.clinica_id', Filament::getTenant()?->id)
                        )
                        ->saveRelationshipsUsing(function (Model $record, $state) {

                            app(\Spatie\Permission\PermissionRegistrar::class)
                                ->setPermissionsTeamId(Filament::getTenant()?->id);

                            $record->roles()->syncWithPivotValues(
                                $state,
                                [config('permission.column_names.team_foreign_key') => Filament::getTenant()?->id]
                            );
                        })
                        ->multiple()
                        ->preload()
                        ->searchable()

                ])->columns(2),
        ]);
    }
}