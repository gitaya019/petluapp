<?php

namespace App\Filament\System\Resources\Users\Pages;

use App\Filament\System\Resources\Users\UserResource;
use Filament\Resources\Pages\EditRecord;
    use Illuminate\Support\Facades\DB;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
            \Filament\Actions\ForceDeleteAction::make(),
            \Filament\Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $roleIds = \Illuminate\Support\Facades\DB::table('model_has_roles')
            ->where('model_id', $this->record->id)
            ->pluck('role_id');

        $roles = \App\Models\Role::whereIn('id', $roleIds)->get(); //ignore this error, is a valid code. Intelephense bug

        $data['clinica_roles'] = $roles->map(fn($role) => [
            'clinica_id' => $role->clinica_id,
            'role_id' => $role->id,
        ])->values()->toArray();

        return $data;
    }


    protected function afterSave(): void
    {
        DB::table('model_has_roles')
            ->where('model_id', $this->record->id)
            ->delete();

        foreach ($this->data['clinica_roles'] ?? [] as $item) {

            DB::table('model_has_roles')->insert([
                'role_id' => $item['role_id'],
                'model_type' => \App\Models\User::class,
                'model_id' => $this->record->id,
                'clinica_id' => $item['clinica_id'],
            ]);
        }
    }
}
