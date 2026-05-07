<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use EslamRedaDiv\FilamentCopilot\Contracts\CopilotResource;

class UserResource extends Resource implements CopilotResource
{
    protected static ?string $model = User::class;

    protected static ?string $tenantOwnershipRelationshipName = 'clinicas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static string|\UnitEnum|null $navigationGroup = 'Admin';

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_super_admin', false);
    }

    public static function copilotResourceDescription(): ?string
    {
        return '
            Gestiona usuarios del sistema veterinario.
            Incluye veterinarios, administradores,
            auxiliares y permisos.
        ';
    }

    public static function copilotTools(): array
    {
        return [
            new \App\Filament\Resources\Users\CopilotTools\ListUsersTool(),
            new \App\Filament\Resources\Users\CopilotTools\SearchUsersTool(),
        ];
    }
}