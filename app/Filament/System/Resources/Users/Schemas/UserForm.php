<?php

namespace App\Filament\System\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;


class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del Usuario')
                ->icon('heroicon-o-user')
                ->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('email')->email()->required(),
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->required(fn(string $operation) => $operation === 'create')
                        ->hidden(fn(string $operation) => $operation === 'edit'),
                    TextInput::make('numero_documento'),
                    Select::make('tipo_documento')
                        ->options([
                            'CC' => 'Cédula',
                            'TI' => 'Tarjeta',
                        ])
                        ->default('CC'),

                    TextInput::make('telefono'),
                    Toggle::make('estado')->default(true),

                    Select::make('roles')
                        ->label('Roles')
                        ->relationship(
                            name: 'roles',
                            titleAttribute: 'name',
                            modifyQueryUsing: fn($query) =>
                            $query->where('roles.clinica_id', Filament::getTenant()?->id)
                        )
                        ->saveRelationshipsUsing(function (Model $record, $state) {

                            // 🔥 SETEAR TENANT (CLAVE)
                            app(\Spatie\Permission\PermissionRegistrar::class)
                                ->setPermissionsTeamId(Filament::getTenant()?->id);

                            // 🔥 GUARDAR CON clinica_id
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
