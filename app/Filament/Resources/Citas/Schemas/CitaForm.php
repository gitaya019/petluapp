<?php

namespace App\Filament\Resources\Citas\Schemas;

use Filament\Schemas\Schema;
use Filament\Facades\Filament;
use App\Models\User;
use App\Models\Mascota;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;

class CitaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('user_id')
                    ->label('Propietario')
                    ->placeholder('Selecciona un propietario')

                    ->loadingMessage('Cargando propietarios...')

                    ->noSearchResultsMessage('No se encontraron propietarios')

                    ->noOptionsMessage(
                        'No hay propietarios disponibles'
                    )

                    ->searchingMessage('Buscando propietarios...')

                    ->searchDebounce(500)

                    ->searchPrompt('Buscar por nombre...')
                    ->searchable()
                    ->live()
                    ->getSearchResultsUsing(
                        fn(string $search) =>

                        User::query()
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('numero_documento', 'like', "%{$search}%")
                            ->orWhere('telefono', 'like', "%{$search}%")
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(fn($user) => [
                                $user->id =>
                                "{$user->name} - {$user->numero_documento}"
                            ])
                    )

                    ->getOptionLabelUsing(
                        fn($value): ?string =>

                        User::find($value)?->name
                    )

                    ->required()
                    ->dehydrated(false),

                Select::make('mascota_id')
                    ->label('Mascota')
                    ->placeholder('Selecciona un mascota')

                    ->loadingMessage('Cargando mascotas...')

                    ->noSearchResultsMessage('No se encontraron mascotas')

                    ->noOptionsMessage(
                        'No hay mascotas disponibles'
                    )

                    ->searchingMessage('Buscando mascotas...')

                    ->searchDebounce(500)

                    ->searchPrompt('Buscar por nombre...')
                    ->options(function ($get) {

                        $userId = $get('user_id');

                        if (! $userId) {
                            return [];
                        }

                        return Mascota::query()
                            ->where('user_id', $userId)
                            ->where('clinica_id', Filament::getTenant()->id)
                            ->pluck('nombre', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('veterinario_id')
                    ->label('Veterinario')

                    ->placeholder('Selecciona un veterinario')

                    ->loadingMessage('Cargando veterinarios...')

                    ->noSearchResultsMessage('No se encontraron veterinarios')

                    ->noOptionsMessage(
                        'No hay veterinarios disponibles'
                    )

                    ->searchingMessage('Buscando veterinarios...')

                    ->searchDebounce(500)

                    ->searchPrompt('Buscar por nombre...')
                    ->options(

                        User::role('Veterinario')
                            ->whereHas(
                                'clinicas',
                                fn($q) =>
                                $q->where('clinicas.id', Filament::getTenant()->id)
                            )
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                Select::make('vacuna_id')
                    ->label('Vacuna')

                    ->placeholder('Selecciona un vacuna')

                    ->loadingMessage('Cargando vacunas...')

                    ->noSearchResultsMessage('No se encontraron vacunas')

                    ->noOptionsMessage(
                        'No hay vacunas disponibles'
                    )

                    ->searchingMessage('Buscando vacunas...')

                    ->searchDebounce(500)

                    ->searchPrompt('Buscar por nombre...')
                    ->relationship(
                        'vacuna',
                        'nombre',
                        fn($query) =>
                        $query->where('clinica_id', Filament::getTenant()->id)
                    )
                    ->searchable()
                    ->preload(),

                DatePicker::make('fecha')
                    ->required()
                    ->native(false)
                    ->minDate(now()),

                TimePicker::make('hora')
                    ->required(),

                Select::make('estado')

                    ->placeholder('Selecciona un estado')

                    ->loadingMessage('Cargando estados...')

                    ->noSearchResultsMessage('No se encontraron estados')

                    ->noOptionsMessage(
                        'No hay estados disponibles'
                    )

                    ->searchingMessage('Buscando estados...')

                    ->searchDebounce(500)

                    ->searchPrompt('Buscar por nombre...')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                    ])
                    ->default('pendiente')
                    ->required(),

                Textarea::make('motivo'),

                Textarea::make('observaciones'),
            ]);
    }
}
