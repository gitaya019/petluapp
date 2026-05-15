<?php

namespace App\Filament\System\Resources\LoteVacunas;

use App\Filament\System\Resources\LoteVacunas\Pages\CreateLoteVacuna;
use App\Filament\System\Resources\LoteVacunas\Pages\EditLoteVacuna;
use App\Filament\System\Resources\LoteVacunas\Pages\ListLoteVacunas;
use App\Filament\System\Resources\LoteVacunas\Schemas\LoteVacunaForm;
use App\Filament\System\Resources\LoteVacunas\Tables\LoteVacunasTable;
use App\Models\LoteVacuna;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class LoteVacunaResource extends Resource implements HasApi
{
    protected static ?string $model = LoteVacuna::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LoteVacunaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LoteVacunasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLoteVacunas::route('/'),
            'create' => CreateLoteVacuna::route('/create'),
            'edit' => EditLoteVacuna::route('/{record}/edit'),
        ];
    }

    public static function apiConfig(): array
    {
        return [
            'allowed_methods'   => ['index', 'show', 'store', 'update', 'destroy'],
            'allowed_filters'   => ['title', 'status', 'category_id'],
            'allowed_sorts'     => ['title', 'created_at', 'published_at'],
            'allowed_includes'  => ['author', 'category'],
            'allowed_fields'    => ['id', 'title', 'slug', 'body', 'status', 'published_at'],
            'searchable'        => ['title', 'body'],
            'scopes'            => ['read', 'write', 'delete'],
            'validation_rules'  => [
                'title'  => ['required', 'string', 'max:255'],
                'body'   => ['required', 'string'],
                'status' => ['required', 'in:draft,published,archived'],
            ],
        ];
    }
}
