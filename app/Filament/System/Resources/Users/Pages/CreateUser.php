<?php

namespace App\Filament\System\Resources\Users\Pages;

use App\Filament\System\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function afterCreate(): void
    {
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
