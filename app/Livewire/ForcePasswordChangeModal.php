<?php

namespace App\Livewire;

use Livewire\Component;

use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Concerns\InteractsWithActions;

use App\Actions\ForceChangePasswordAction;

class ForcePasswordChangeModal extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function forceChangePasswordAction(): Action
    {
        return ForceChangePasswordAction::make();
    }

    public function render()
    {
        return view('livewire.force-password-change-modal');
    }
}