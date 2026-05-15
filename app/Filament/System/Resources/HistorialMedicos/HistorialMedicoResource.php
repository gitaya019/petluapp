<?php

namespace App\Filament\System\Resources\HistorialMedicos;

use App\Filament\System\Resources\HistorialMedicos\Pages\CreateHistorialMedico;
use App\Filament\System\Resources\HistorialMedicos\Pages\EditHistorialMedico;
use App\Filament\System\Resources\HistorialMedicos\Pages\ListHistorialMedicos;
use App\Filament\System\Resources\HistorialMedicos\Schemas\HistorialMedicoForm;
use App\Filament\System\Resources\HistorialMedicos\Tables\HistorialMedicosTable;
use App\Models\HistorialMedico;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class HistorialMedicoResource extends Resource implements HasApi
{
    protected static ?string $model = HistorialMedico::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HistorialMedicoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistorialMedicosTable::configure($table);
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
            'index' => ListHistorialMedicos::route('/'),
            'create' => CreateHistorialMedico::route('/create'),
            'edit' => EditHistorialMedico::route('/{record}/edit'),
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
