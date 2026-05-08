<?php

namespace App\Filament\Resources\Mascotas\Schemas;

use App\Models\User;

use Filament\Schemas\Schema;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\DatePicker;

class MascotaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Información de la mascota')
                ->icon('heroicon-o-heart')

                ->schema([

                    // 🪪 DOCUMENTO DEL DUEÑO
                    TextInput::make('documento_cliente')
                        ->label('Documento del dueño')
                        ->placeholder('Ingresa el número de documento')
                        ->live(debounce: 500)

                        ->helperText(
                            'Escribe el número de documento del propietario para buscarlo automáticamente.'
                        )

                        ->dehydrated(false)

                        ->afterStateUpdated(function ($state, callable $set) {

                            $cliente = User::query()
                                ->where('numero_documento', $state)
                                ->first();

                            if ($cliente) {

                                $set('user_id', $cliente->id);

                                $set(
                                    'cliente_nombre',
                                    $cliente->name
                                );

                                $set(
                                    'cliente_estado',
                                    '✅ Cliente encontrado correctamente'
                                );

                                return;
                            }

                            // ❌ NO EXISTE
                            $set('user_id', null);

                            $set('cliente_nombre', null);

                            $set(
                                'cliente_estado',
                                '❌ No existe ningún usuario con ese documento'
                            );
                        })

                        ->required(),

                    // 🔒 USER ID REAL
                    Hidden::make('user_id')
                        ->required(),

                    // 👤 NOMBRE DEL CLIENTE
                    TextInput::make('cliente_nombre')
                        ->label('Propietario encontrado')
                        ->readOnly()
                        ->dehydrated(false)

                        ->placeholder('Aquí aparecerá el nombre del propietario'),

                    // 📢 ESTADO BÚSQUEDA
                    Placeholder::make('estado_cliente')
                        ->label('Estado de búsqueda')

                        ->content(function (callable $get) {

                            return $get('cliente_estado')
                                ?: 'Esperando búsqueda...';
                        }),

                    // 🐶 NOMBRE
                    TextInput::make('nombre')
                        ->label('Nombre de la mascota')
                        ->required(),

                    // 🐾 ESPECIE
                    TextInput::make('especie')
                        ->label('Especie')
                        ->required(),

                    // 🧬 RAZA
                    TextInput::make('raza')
                        ->label('Raza'),

                    // 🎂 FECHA NACIMIENTO
                    DatePicker::make('fecha_nacimiento')
                        ->label('Fecha de nacimiento'),

                    // ⚧ SEXO
                    TextInput::make('sexo')
                        ->label('Sexo'),

                    // ⚖ PESO
                    TextInput::make('peso')
                        ->label('Peso (kg)')
                        ->numeric(),

                    // 🎨 COLOR
                    TextInput::make('color')
                        ->label('Color'),

                    // ✅ ESTADO
                    Toggle::make('estado')
                        ->label('Activo')
                        ->default(true),

                ])

                ->columns(2),
        ]);
    }
}