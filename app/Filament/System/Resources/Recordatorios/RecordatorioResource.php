<?php

namespace App\Filament\System\Resources\Recordatorios;

use App\Filament\System\Resources\Recordatorios\Pages\CreateRecordatorio;
use App\Filament\System\Resources\Recordatorios\Pages\EditRecordatorio;
use App\Filament\System\Resources\Recordatorios\Pages\ListRecordatorios;
use App\Filament\System\Resources\Recordatorios\Schemas\RecordatorioForm;
use App\Filament\System\Resources\Recordatorios\Tables\RecordatoriosTable;
use App\Models\Recordatorio;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use YusufGenc34\FilamentApiForge\Contracts\HasApi;


class RecordatorioResource extends Resource implements HasApi
{
    protected static ?string $model = Recordatorio::class;

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RecordatorioForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecordatoriosTable::configure($table);
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
            'index' => ListRecordatorios::route('/'),
            'create' => CreateRecordatorio::route('/create'),
            'edit' => EditRecordatorio::route('/{record}/edit'),
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
