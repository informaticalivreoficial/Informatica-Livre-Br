<div>
    @if($sent)
        <div class="bg-teal-50 border border-teal-200 rounded-xl p-6 text-center">
            <i class="fas fa-check-circle text-teal-500 text-5xl mb-3"></i>
            <h3 class="text-xl font-bold text-teal-700 mb-2">Mensagem enviada!</h3>
            <p class="text-teal-600 mb-4">Obrigado pelo contato. Responderemos em breve.</p>
            <button wire:click="$set('sent', false)" class="text-teal-600 hover:underline text-sm">
                Enviar outra mensagem
            </button>
        </div>
    @else
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Envie uma mensagem</h2>
        <div class="space-y-5">
            {{-- HONEYPOT --}}
            <input type="hidden" wire:model="bairro">
            <input type="text" class="hidden" wire:model="cidade">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                    <input
                        type="text"
                        wire:model="name"
                        placeholder="Seu nome completo"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('name') border-red-400 @enderror"
                    >
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail *</label>
                    <input
                        type="email"
                        wire:model="email"
                        placeholder="seu@email.com"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('email') border-red-400 @enderror"
                    >
                    @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                    <input
                        type="text"
                        wire:model="phone"
                        placeholder="(00) 00000-0000"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Assunto *</label>
                    <input
                        type="text"
                        wire:model="subject"
                        placeholder="Ex: Orçamento para site"
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('subject') border-red-400 @enderror"
                    >
                    @error('subject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mensagem *</label>
                <textarea
                    wire:model="message"
                    rows="6"
                    placeholder="Descreva como podemos ajudar..."
                    class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('message') border-red-400 @enderror"
                ></textarea>
                @error('message') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button
                wire:click="send"
                wire:loading.attr="disabled"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2"
            >
                <span wire:loading.remove wire:target="send">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar Mensagem
                </span>
                <span wire:loading wire:target="send">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Enviando...
                </span>
            </button>
        </div>
    @endif
</div>