<div class="space-y-4">

    @foreach($planos as $plano)
        <div
            wire:click="selecionar({{ $plano->id }})"
            class="relative rounded-xl border-2 cursor-pointer transition p-5
                {{ $planoSelecionado === $plano->id
                    ? 'border-teal-500 bg-teal-50'
                    : 'border-gray-200 bg-white hover:border-teal-300' }}"
        >
            {{-- Badge destaque --}}
            @if($plano->destaque)
                <span class="absolute -top-3 left-4 bg-teal-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                    Mais popular
                </span>
            @endif

            {{-- Selecionado --}}
            <div class="flex items-start justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 mt-0.5
                        {{ $planoSelecionado === $plano->id ? 'border-teal-500' : 'border-gray-300' }}">
                        @if($planoSelecionado === $plano->id)
                            <div class="w-2.5 h-2.5 rounded-full bg-teal-500"></div>
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $plano->nome }}</p>
                        @if($plano->descricao)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $plano->descricao }}</p>
                        @endif
                    </div>
                </div>

                {{-- Preço --}}
                <div class="text-right flex-shrink-0">
                    @if($plano->tem_desconto)
                        <p class="text-xs text-gray-400 line-through">{{ $plano->preco_de_formatado }}</p>
                    @endif
                    <p class="text-xl font-extrabold text-teal-600">{{ $plano->preco_formatado }}</p>
                    @if($plano->tem_desconto)
                        <span class="text-xs font-bold text-green-600">-{{ $plano->percentual_desconto }}%</span>
                    @endif
                </div>
            </div>

            {{-- Itens inclusos --}}
            @if($plano->incluso)
                <ul class="mt-4 space-y-1.5 border-t border-gray-100 pt-4">
                    @foreach($plano->incluso as $item)
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <i class="fas fa-check text-teal-500 text-xs flex-shrink-0"></i>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endforeach

    {{-- Botão comprar --}}
    <button
        wire:click="irParaCheckout"
        wire:loading.attr="disabled"
        @disabled(!$planoSelecionado)
        class="w-full bg-teal-600 hover:bg-teal-700 disabled:bg-gray-300 text-white px-8 py-4 rounded-xl font-bold text-lg transition flex items-center justify-center gap-2 mt-2"
    >
        <span wire:loading.remove wire:target="irParaCheckout">
            <i class="fas fa-shopping-cart mr-2"></i> Comprar agora
        </span>
        <span wire:loading wire:target="irParaCheckout">
            <i class="fas fa-spinner fa-spin mr-2"></i> Aguarde...
        </span>
    </button>

    <p class="text-center text-xs text-gray-400">
        <i class="fas fa-shield-alt mr-1"></i> Pagamento seguro · Instalação inclusa
    </p>

</div>