<div>
    <div
        x-data="{
            open: @entangle('showSupport'),
            toggleBody() {
                document.body.classList.toggle('overflow-hidden', this.open);
            }
        }"
        x-effect="toggleBody()"
        x-show="open"
        x-transition.opacity
        x-cloak
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-[1055] flex items-center justify-center"
    >
        <!-- Overlay -->
        <div
            class="absolute inset-0 bg-black/40"
            aria-hidden="true"
            @click="open = false"
        ></div>

        <!-- Modal -->
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Suporte</h2>

            <textarea
                wire:model.defer="message"
                rows="6"
                class="w-full p-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500"
                placeholder="Descreva sua solicitação..."
            ></textarea>

            @error('message')
                <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex justify-end gap-2 mt-4">
                <button
                    wire:click="$set('showSupport', false)"
                    class="px-4 py-2 bg-gray-200 rounded"
                >
                    Cancelar
                </button>

                <button
                    wire:click="sendSupport"
                    class="px-4 py-2 bg-green-500 text-white rounded"
                >
                    Solicitar suporte
                </button>
            </div>
        </div>
    </div>
</div>