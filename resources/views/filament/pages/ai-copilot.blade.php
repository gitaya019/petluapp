<x-filament-panels::page>

    <div
        class="
            max-w-6xl
            mx-auto
            h-[78vh]
            flex
            flex-col
        "
    >

        {{-- CHAT --}}

        <div

            x-data
            x-init="$el.scrollTop = $el.scrollHeight"
            x-effect="$el.scrollTop = $el.scrollHeight"

            class="
                flex-1
                overflow-y-auto
                rounded-3xl
                border
                border-gray-200
                dark:border-white/10
                bg-white
                dark:bg-gray-900
                p-6
                space-y-6
                shadow-sm
            "
        >

            @forelse ($messages as $msg)

                <div
                    class="
                        flex

                        {{ $msg['type'] === 'user'
                            ? 'justify-end'
                            : 'justify-start'
                        }}
                    "
                >

                    <div
                        class="
                            max-w-3xl
                            rounded-3xl
                            px-5
                            py-4
                            whitespace-pre-wrap
                            text-sm
                            shadow-sm

                            {{ $msg['type'] === 'user'
                                ? 'bg-primary-600 text-white'
                                : ''
                            }}

                            {{ $msg['type'] === 'assistant'
                                ? 'bg-gray-100 dark:bg-white/5'
                                : ''
                            }}

                            {{ $msg['type'] === 'error'
                                ? 'bg-danger-600 text-white'
                                : ''
                            }}
                        "
                    >

                        <div
                            class="
                                text-xs
                                font-semibold
                                mb-2
                                opacity-70
                            "
                        >

                            @if ($msg['type'] === 'user')
                                Tú
                            @elseif ($msg['type'] === 'assistant')
                                AI Copilot
                            @else
                                Error
                            @endif

                        </div>

                        {{ $msg['content'] }}

                    </div>

                </div>

            @empty

                <div
                    class="
                        h-full
                        flex
                        items-center
                        justify-center
                    "
                >

                    <div
                        class="
                            text-center
                            space-y-4
                        "
                    >

                        <div class="text-6xl">
                            ✨
                        </div>

                        <div
                            class="
                                text-2xl
                                font-bold
                            "
                        >
                            AI Copilot
                        </div>

                        <div
                            class="
                                text-gray-500
                                dark:text-gray-400
                            "
                        >
                            Consulta mascotas,
                            vacunas,
                            citas,
                            ventas
                            y más.
                        </div>

                    </div>

                </div>

            @endforelse

            {{-- LOADING --}}

            @if ($loading)

                <div class="flex justify-start">

                    <div
                        class="
                            bg-gray-100
                            dark:bg-white/5
                            rounded-3xl
                            px-5
                            py-4
                        "
                    >

                        <div
                            class="
                                flex
                                gap-2
                            "
                        >

                            <div
                                class="
                                    w-2
                                    h-2
                                    rounded-full
                                    bg-primary-500
                                    animate-bounce
                                "
                            ></div>

                            <div
                                class="
                                    w-2
                                    h-2
                                    rounded-full
                                    bg-primary-500
                                    animate-bounce
                                    [animation-delay:0.2s]
                                "
                            ></div>

                            <div
                                class="
                                    w-2
                                    h-2
                                    rounded-full
                                    bg-primary-500
                                    animate-bounce
                                    [animation-delay:0.4s]
                                "
                            ></div>

                        </div>

                    </div>

                </div>

            @endif

        </div>

        {{-- INPUT --}}

        <form
            wire:submit="send"
            class="mt-4"
        >

            <div
                class="
                    flex
                    items-end
                    gap-3
                    rounded-3xl
                    border
                    border-gray-200
                    dark:border-white/10
                    bg-white
                    dark:bg-gray-900
                    p-3
                    shadow-sm
                "
            >

                <textarea
                    wire:model="message"
                    rows="1"
                    placeholder="Pregunta algo..."
                    class="
                        w-full
                        resize-none
                        border-0
                        bg-transparent
                        focus:ring-0
                        text-sm
                    "
                ></textarea>

                <x-filament::button
                    type="submit"
                    icon="heroicon-m-paper-airplane"
                    class="cursor-pointer"
                >
                    Enviar
                </x-filament::button>

            </div>

        </form>

    </div>

</x-filament-panels::page>