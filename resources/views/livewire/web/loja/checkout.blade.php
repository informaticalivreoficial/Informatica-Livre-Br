<div>
    @php
        $recorrente = $planoSelecionado->isRecurring();
    @endphp

    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-14">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('web.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('web.produtos') }}" class="hover:text-white transition">Sistemas</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('web.produto', $produtoSelecionado->slug) }}" class="hover:text-white transition">{{ $produtoSelecionado->nome }}</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white">Checkout</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-white">Finalizar pedido</h1>
            <p class="text-teal-100 mt-2">Confirme seus dados e escolha a forma de pagamento.</p>
        </div>
    </section>

    <section class="py-14 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">

            @if ($pedido)
                <div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-check text-2xl"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Pedido registrado!</h2>
                    <p class="text-gray-500 mb-6">
                        Guarde o código <strong class="text-gray-700">{{ $pedido->codigo }}</strong>.
                        Os dados de acesso serão enviados para <strong class="text-gray-700">{{ $pedido->email }}</strong>.
                    </p>

                    <div class="rounded-xl bg-gray-50 border border-gray-100 p-5 text-left space-y-2 mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Produto</span>
                            <span class="text-gray-800 font-medium">{{ $produtoSelecionado->nome }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Plano</span>
                            <span class="text-gray-800 font-medium">{{ $planoSelecionado->nome }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Forma de pagamento</span>
                            <span class="text-gray-800 font-medium">{{ $pedido->metodo_pagamento_label }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                {{ $pedido->status_pagamento_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                            <span class="text-gray-500 text-sm">Total</span>
                            <span class="text-lg font-bold text-gray-800">{{ $pedido->total_formatado }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if ($pedido->payment_url)
                            <a
                                href="{{ $pedido->payment_url }}"
                                target="_blank"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-semibold transition"
                            >
                                <i class="fas fa-external-link-alt"></i> Abrir pagamento
                            </a>
                        @else
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-envelope mr-1"></i>
                                Enviaremos o link de pagamento para o seu e-mail em instantes.
                            </p>
                        @endif

                        <a
                            href="{{ route('web.produtos') }}"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition"
                        >
                            Ver outros sistemas
                        </a>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="finalizar">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h2 class="font-bold text-gray-800">Seus dados</h2>
                                </div>

                                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome completo *</label>
                                        <input
                                            type="text"
                                            wire:model="nome"
                                            placeholder="Como no documento"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('nome') border-red-400 @enderror"
                                        >
                                        @error('nome') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">E-mail *</label>
                                        <input
                                            type="email"
                                            wire:model="email"
                                            placeholder="voce@empresa.com.br"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @enderror"
                                        >
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Telefone / WhatsApp *</label>
                                        <input
                                            type="tel"
                                            wire:model="telefone"
                                            placeholder="(00) 00000-0000"
                                            x-data
                                            x-init="IMask($el, { mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }] })"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('telefone') border-red-400 @enderror"
                                        >
                                        @error('telefone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">CPF ou CNPJ *</label>
                                        <input
                                            type="text"
                                            wire:model="cpfCnpj"
                                            placeholder="000.000.000-00"
                                            x-data
                                            x-init="IMask($el, { mask: [{ mask: '000.000.000-00' }, { mask: '00.000.000/0000-00' }] })"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('cpfCnpj') border-red-400 @enderror"
                                        >
                                        @error('cpfCnpj') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Observações</label>
                                        <textarea
                                            wire:model="observacoes"
                                            rows="3"
                                            placeholder="Informações adicionais sobre o projeto (opcional)"
                                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('observacoes') border-red-400 @enderror"
                                        ></textarea>
                                        @error('observacoes') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h2 class="font-bold text-gray-800">Forma de pagamento</h2>
                                </div>

                                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <button
                                        type="button"
                                        wire:click="$set('metodoPagamento', 'pix')"
                                        class="flex items-center gap-4 rounded-xl border-2 px-5 py-4 text-left transition {{ $metodoPagamento === 'pix' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-teal-300' }}"
                                    >
                                        <span class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                                            <i class="fas fa-qrcode"></i>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-gray-800">PIX</span>
                                            <span class="block text-xs text-gray-500">Aprovação imediata</span>
                                        </span>
                                    </button>

                                    <button
                                        type="button"
                                        wire:click="$set('metodoPagamento', 'boleto')"
                                        class="flex items-center gap-4 rounded-xl border-2 px-5 py-4 text-left transition {{ $metodoPagamento === 'boleto' ? 'border-teal-500 bg-teal-50' : 'border-gray-200 hover:border-teal-300' }}"
                                    >
                                        <span class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <span>
                                            <span class="block text-sm font-semibold text-gray-800">Boleto</span>
                                            <span class="block text-xs text-gray-500">Vencimento em 3 dias</span>
                                        </span>
                                    </button>

                                    @error('metodoPagamento') <span class="text-red-500 text-xs block sm:col-span-2">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1">
                            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm sticky top-24 overflow-hidden">
                                <div class="px-6 py-4 border-b border-gray-100">
                                    <h2 class="font-bold text-gray-800">Resumo do pedido</h2>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                            <img src="{{ $produtoSelecionado->cover_url }}" alt="{{ $produtoSelecionado->nome }}" class="w-full h-full object-cover">
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-800">{{ $produtoSelecionado->nome }}</p>
                                            <p class="text-xs text-gray-500">{{ $planoSelecionado->nome }}</p>

                                            @if ($planoSelecionado->isRecurring())
                                                <span class="inline-flex items-center gap-1 mt-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-600">
                                                    <i class="fas fa-sync-alt text-[9px]"></i> {{ $planoSelecionado->billingLabel() }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($planoSelecionado->incluso_lista)
                                        <ul class="space-y-1.5 border-t border-gray-100 pt-4">
                                            @foreach ($planoSelecionado->incluso_lista as $item)
                                                <li class="flex items-start gap-2 text-xs text-gray-600">
                                                    <i class="fas fa-check text-teal-500 mt-0.5"></i>
                                                    {{ $item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div class="space-y-2 border-t border-gray-100 pt-4 text-sm">
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-500">Subtotal</span>
                                            <span class="text-gray-800 font-medium">{{ $planoSelecionado->preco_formatado }}</span>
                                        </div>

                                        @if ($planoSelecionado->tem_desconto)
                                            <div class="flex items-center justify-between">
                                                <span class="text-gray-500">De</span>
                                                <span class="text-gray-400 line-through">{{ $planoSelecionado->preco_de_formatado }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-500">Desconto</span>
                                            <span class="text-green-600 font-medium">R$ 0,00</span>
                                        </div>

                                        <div class="flex items-end justify-between border-t border-gray-200 pt-3">
                                            <span class="text-gray-500">Total hoje</span>
                                            <div class="text-right">
                                                <p class="text-2xl font-extrabold text-gray-800">{{ $planoSelecionado->preco_formatado }}</p>
                                                @if ($recorrente)
                                                    <p class="text-[11px] text-gray-400">renova por {{ $planoSelecionado->preco_formatado }}{{ $planoSelecionado->periodo }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        type="submit"
                                        wire:loading.attr="disabled"
                                        class="w-full inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 text-white px-6 py-4 rounded-xl font-bold transition"
                                    >
                                        <span wire:loading.remove wire:target="finalizar">
                                            <i class="fas fa-lock mr-1"></i> Confirmar pedido
                                        </span>
                                        <span wire:loading wire:target="finalizar">
                                            <i class="fas fa-spinner fa-spin mr-1"></i> Processando...
                                        </span>
                                    </button>

                                    <p class="text-center text-[11px] text-gray-400">
                                        <i class="fas fa-shield-alt mr-1"></i> Ambiente seguro · Dados criptografados
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            @endif

        </div>
    </section>
</div>
