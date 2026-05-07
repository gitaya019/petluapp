<?php

namespace App\Filament\Resources\Mascotas;

use App\Filament\Resources\Mascotas\Pages\CreateMascota;
use App\Filament\Resources\Mascotas\Pages\EditMascota;
use App\Filament\Resources\Mascotas\Pages\ListMascotas;
use App\Filament\Resources\Mascotas\Schemas\MascotaForm;
use App\Filament\Resources\Mascotas\Tables\MascotasTable;
use App\Models\Mascota;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;

class MascotaResource extends Resource implements CopilotResource
{
    protected static ?string $model = Mascota::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Heart;

    protected static string|\UnitEnum|null $navigationGroup = 'Veterinaria';

    public static function form(Schema $schema): Schema
    {
        return MascotaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MascotasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMascotas::route('/'),
            'create' => CreateMascota::route('/create'),
            'edit' => EditMascota::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('clinica_id', Filament::getTenant()->id);
    }

    public static function copilotResourceDescription(): ?string
    {
        return '
            Gestiona mascotas veterinarias.
            Incluye información de propietarios, especie,
            raza, vacunas, historial médico y estado clínico.
        ';
    }

    public static function copilotTools(): array
    {
        return [
            new \App\Filament\Resources\Mascotas\CopilotTools\ListMascotasTool(),
            new \App\Filament\Resources\Mascotas\CopilotTools\SearchMascotasTool(),
            new \App\Filament\Resources\Mascotas\CopilotTools\ViewMascotaTool(),
        ];
    }
}