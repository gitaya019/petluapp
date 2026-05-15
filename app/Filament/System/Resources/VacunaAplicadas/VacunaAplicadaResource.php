<?php

namespace App\Filament\System\Resources\VacunaAplicadas;

use App\Filament\System\Resources\VacunaAplicadas\Pages\CreateVacunaAplicada;
use App\Filament\System\Resources\VacunaAplicadas\Pages\EditVacunaAplicada;
use App\Filament\System\Resources\VacunaAplicadas\Pages\ListVacunaAplicadas;
use App\Filament\System\Resources\VacunaAplicadas\Schemas\VacunaAplicadaForm;
use App\Filament\System\Resources\VacunaAplicadas\Tables\VacunaAplicadasTable;
use App\Models\VacunaAplicada;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class VacunaAplicadaResource extends Resource implements HasApi
{
    protected static ?string $model = VacunaAplicada::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VacunaAplicadaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VacunaAplicadasTable::configure($table);
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
            'index' => ListVacunaAplicadas::route('/'),
            'create' => CreateVacunaAplicada::route('/create'),
            'edit' => EditVacunaAplicada::route('/{record}/edit'),
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
