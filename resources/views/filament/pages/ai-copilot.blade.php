<x-filament-panels::page>

    <form wire:submit="send">

        <x-filament::input.wrapper>
            <x-filament::input
                wire:model="message"
                placeholder="Pregunta algo..."
            />
        </x-filament::input.wrapper>

        <x-filament::button
            type="submit"
            class="mt-4 cursor-pointer"
        >
            Enviar
        </x-filament::button>

    </form>

    <div class="mt-6">
        {{ $response }}
    </div>

</x-filament-panels::page>