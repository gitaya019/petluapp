<x-filament-panels::page>
    <div
        class="w-full max-w-6xl mx-auto h-[85vh] flex flex-col"
        x-data="chatComponent()"
        x-init="init()"
    >

        {{-- HEADER MODERNO --}}
        <div class="mb-6 text-center space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gradient-to-r from-primary-500/10 to-purple-500/10 dark:from-primary-500/20 dark:to-purple-500/20 text-sm font-medium text-primary-600 dark:text-primary-400">
                <span class="text-base">✨</span>
                <span>AI Veterinaria</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold bg-gradient-to-r from-gray-900 via-primary-600 to-purple-600 dark:from-white dark:via-primary-400 dark:to-purple-400 bg-clip-text text-transparent">
                AI Copilot
            </h1>

            <p class="text-gray-500 dark:text-gray-400 text-base max-w-2xl mx-auto">
                Tu asistente inteligente para gestionar mascotas, vacunas, citas y más
            </p>
        </div>

        {{-- CHAT CONTAINER --}}
        <div class="flex-1 flex flex-col overflow-hidden rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-200 dark:border-gray-800">

            {{-- MENSAJES --}}
            <div x-ref="messagesContainer" class="flex-1 overflow-y-auto px-6 py-6 space-y-4">
                
                @forelse ($messages as $index => $msg)
                    <div class="flex w-full {{ $msg['type'] === 'user' ? 'justify-end' : 'justify-start' }} animate-in fade-in slide-in-from-bottom-2 duration-300" style="animation-delay: {{ $index * 50 }}ms">
                        <div class="flex gap-2 max-w-[85%] md:max-w-[75%] lg:max-w-[65%] group">
                            
                            {{-- AVATAR ASISTENTE --}}
                            @if($msg['type'] !== 'user')
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white shadow-md mt-1">
                                    <span class="text-xs">✨</span>
                                </div>
                            @endif

                            {{-- BURBUJA DEL MENSAJE --}}
                            <div class="relative rounded-2xl px-4 py-2 {{ $msg['type'] === 'user' ? 'bg-primary-600 text-white' : ($msg['type'] === 'error' ? 'bg-red-500 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100') }}">
                                
                                {{-- CONTENIDO --}}
                                <div class="text-sm leading-relaxed whitespace-pre-wrap break-words">
                                    {!! nl2br(e($msg['content'])) !!}
                                </div>

                                {{-- TIMESTAMP Y BOTÓN COPIAR --}}
                                <div class="flex items-center justify-end gap-2 mt-1">
                                    <span class="text-xs opacity-60">{{ now()->format('H:i') }}</span>
                                    
                                    @if($msg['type'] === 'assistant')
                                        <button @click="copyToClipboard('{{ addslashes($msg['content']) }}')" class="opacity-0 group-hover:opacity-100 transition-opacity p-1 rounded hover:bg-black/10 dark:hover:bg-white/10">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- AVATAR USUARIO --}}
                            @if($msg['type'] === 'user')
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-gray-600 to-gray-800 flex items-center justify-center text-white shadow-md mt-1">
                                    <span class="text-xs">👤</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty

                    {{-- EMPTY STATE --}}
                    <div class="h-full flex items-center justify-center py-12">
                        <div class="text-center max-w-md space-y-6">
                            <div class="relative w-24 h-24 mx-auto">
                                <div class="absolute inset-0 rounded-full bg-gradient-to-r from-primary-500 to-purple-600 animate-pulse opacity-20"></div>
                                <div class="relative w-full h-full rounded-full bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-4xl shadow-2xl">
                                    ✨
                                </div>
                            </div>

                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    ¡Hola! Soy tu asistente IA
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">
                                    Pregúntame sobre mascotas, vacunas, citas, historiales médicos, ventas y recordatorios.
                                </p>
                            </div>

                            {{-- SUGERENCIAS --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-2">
                                @foreach([
                                    '📋 Ver mascotas registradas',
                                    '💉 Próximas vacunas',
                                    '📅 Citas de hoy',
                                    '💰 Ventas del mes'
                                ] as $suggestion)
                                    <button wire:click="sendSuggestion('{{ $suggestion }}')" class="text-left px-3 py-2 rounded-xl bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all text-sm text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 hover:scale-105">
                                        {{ $suggestion }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforelse

                {{-- LOADING --}}
                @if ($loading)
                    <div class="flex justify-start animate-in fade-in duration-300">
                        <div class="flex gap-2">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-purple-600 flex items-center justify-center text-white shadow-md mt-1">
                                <span class="text-xs">✨</span>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-800 rounded-2xl px-4 py-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0s"></div>
                                    <div class="w-2 h-2 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0.15s"></div>
                                    <div class="w-2 h-2 rounded-full bg-primary-500 animate-bounce" style="animation-delay: 0.30s"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- INPUT --}}
            <div class="border-t border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 p-4">
                <form wire:submit="send" class="relative">
                    <div class="flex items-end gap-2 rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500">
                        <textarea x-data="autoResizeTextarea()" x-init="init" x-model="text" @input="resize" wire:model="message" @keydown.enter.prevent="if (!$event.shiftKey) $wire.send()" rows="1" placeholder="Escribe tu mensaje... (Shift + Enter para nueva línea)" class="w-full resize-none border-0 bg-transparent focus:ring-0 text-sm min-h-[40px] max-h-[120px] py-2 px-3 placeholder:text-gray-400 dark:placeholder:text-gray-600"></textarea>
                        
                        <div class="flex items-center gap-1 pr-2 pb-2">
                            <x-filament::button type="submit" icon="heroicon-m-paper-airplane" size="sm" :disabled="$loading" class="rounded-xl px-3 py-1.5 !bg-gradient-to-r !from-primary-600 !to-primary-700 hover:!from-primary-700 hover:!to-primary-800 transition-all disabled:opacity-50">
                                Enviar
                            </x-filament::button>
                        </div>
                    </div>
                </form>
                
                <div class="text-center text-xs text-gray-400 mt-2">
                    La IA puede cometer errores. Verifica información importante.
                </div>
            </div>

        </div>

    </div>

    <style>
        /* Scrollbar personalizada */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-track {
            background: #2d2d2d;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #4a4a4a;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeIn 0.3s ease-out forwards;
            opacity: 0;
        }

        .fade-in {
            animation-name: fadeIn;
        }

        .slide-in-from-bottom-2 {
            animation-name: fadeIn;
        }
    </style>

    <script>
        function chatComponent() {
            return {
                init() {
                    this.scrollToBottom();
                    // Observar cambios en los mensajes para hacer scroll
                    const observer = new MutationObserver(() => {
                        this.scrollToBottom();
                    });
                    observer.observe(this.$refs.messagesContainer, { 
                        childList: true, 
                        subtree: true 
                    });
                },
                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },
                copyToClipboard(text) {
                    navigator.clipboard.writeText(text);
                    // Puedes mostrar un toast notification aquí
                    console.log('Copiado al portapapeles');
                }
            }
        }

        function autoResizeTextarea() {
            return {
                text: '',
                init() {
                    this.text = this.$el.value;
                    this.resize();
                },
                resize() {
                    const textarea = this.$el;
                    textarea.style.height = 'auto';
                    textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
                }
            }
        }
    </script>
</x-filament-panels::page>