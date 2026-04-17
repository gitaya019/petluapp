<?php

namespace App\Filament\Resources\Clinicas\Pages;

use App\Filament\Resources\Clinicas\ClinicaResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Facades\Filament;

class ListClinicas extends ListRecords
{
    protected static string $resource = ClinicaResource::class;

    public function mount(): void
    {
        parent::mount();

        $tenantId = Filament::getTenant()?->id;

        if ($tenantId) {
            redirect()->to(
                ClinicaResource::getUrl('edit', ['record' => $tenantId])
            );
        }
    }

}