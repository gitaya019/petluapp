<div>

    @if(auth()->user()?->force_password_change)

        <div
            x-data
            x-init="
                setTimeout(() => {
                    $wire.mountAction('forceChangePasswordAction')
                }, 500)
            "
        >
        </div>

    @endif

    <x-filament-actions::modals />

</div>