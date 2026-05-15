<?php

namespace App\Filament\System\Resources\Mascotas;

use App\Filament\System\Resources\Mascotas\Pages\CreateMascota;
use App\Filament\System\Resources\Mascotas\Pages\EditMascota;
use App\Filament\System\Resources\Mascotas\Pages\ListMascotas;
use App\Filament\System\Resources\Mascotas\Schemas\MascotaForm;
use App\Filament\System\Resources\Mascotas\Tables\MascotasTable;
use App\Models\Mascota;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class MascotaResource extends Resource implements HasApi
{
    protected static ?string $model = Mascota::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMascotas::route('/'),
            'create' => CreateMascota::route('/create'),
            'edit' => EditMascota::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
