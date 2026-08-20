<div>
    @if ($show)
        <div
            x-data
            x-cloak
            x-transition.opacity
            @keydown.escape.window="$wire.show = false"
            class="fixed inset-0 z-[1055] flex items-center justify-center p-4"
        >
            <div
                class="absolute inset-0 bg-black/40"
                @click="$wire.show = false"
            ></div>

            <div
                @click.stop
                x-transition.scale
                class="relative bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-lg p-6"
            >
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-slate-100 flex items-center gap-2">
                        <i class="fas fa-envelope text-teal-600"></i> Enviar E-mail
                    </h2>
                    <button
                        type="button"
                        wire:click="close"
                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition"
                    >
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Para</label>
                            <input
                                type="text"
                                value="{{ $recipient }}"
                                readonly
                                class="w-full border border-gray-200 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-slate-300"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">De</label>
                            <input
                                type="text"
                                value="{{ auth()->user()->email }}"
                                readonly
                                class="w-full border border-gray-200 dark:border-slate-600 rounded-lg px-3 py-2 text-sm bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-slate-300"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Assunto</label>
                        <input
                            type="text"
                            wire:model="assunto"
                            placeholder="Assunto do e-mail"
                            class="w-full border border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                        >
                        @error('assunto')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide mb-1.5">Mensagem</label>
                        <textarea
                            wire:model="mensagem"
                            rows="6"
                            class="w-full border border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            placeholder="Escreva sua mensagem..."
                        ></textarea>
                        @error('mensagem')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-5">
                    <button
                        type="button"
                        wire:click="close"
                        class="inline-flex items-center gap-2 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-200 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 dark:hover:bg-slate-600 transition"
                    >
                        Cancelar
                    </button>
                    <button
                        type="button"
                        wire:click="send"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition"
                    >
                        <i class="fas fa-paper-plane text-xs"></i>
                        <span wire:loading.remove wire:target="send">Enviar</span>
                        <span wire:loading wire:target="send">Enviando...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>