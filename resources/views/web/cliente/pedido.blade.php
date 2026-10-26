@extends('web.cliente.layouts.app')

@section('title', 'Pedido ' . $pedido->codigo . ' - ' . $company->alias_name)

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('cliente.pedidos') }}" class="text-sm text-teal-600 hover:underline">
                <i class="fas fa-arrow-left mr-1"></i> Voltar para pedidos
            </a>
            <h1 class="text-2xl font-bold text-gray-800 mt-2">Pedido {{ $pedido->codigo }}</h1>
            <p class="text-gray-500 mt-1">Realizado em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if($pedido->payment_url && ! in_array($pedido->status_pagamento, ['pago', 'cancelado']))
                <a href="{{ $pedido->payment_url }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-credit-card"></i> Pagar agora
                </a>
            @endif

            @if($pedido->status_pagamento === 'pendente')
                <form method="POST" action="{{ route('cliente.pedido.reenviar', $pedido->codigo) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                        <i class="fas fa-paper-plane"></i> Reenviar link
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">Itens</h2>

                    @if($pedido->status_pagamento === 'pago')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Pago</span>
                    @elseif($pedido->status_pagamento === 'pendente')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Aguardando pagamento</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">{{ $pedido->status_pagamento_label }}</span>
                    @endif
                </div>

                <div class="divide-y divide-gray-50">
                    @foreach($pedido->itens as $item)
                        <div class="px-6 py-4 flex items-start justify-between gap-4">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $item->produto_nome }}</p>
                                <p class="text-sm text-gray-500">{{ $item->plano_nome }}</p>
                            </div>

                            <p class="font-semibold text-gray-800">{{ $item->preco_formatado }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 flex items-center justify-between">
                    <span class="text-sm text-gray-500">Total</span>
                    <span class="text-xl font-bold text-gray-800">{{ $pedido->total_formatado }}</span>
                </div>
            </div>

            @if($pedido->licencas->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">Licenças</h2>
                    </div>

                    <div class="divide-y divide-gray-50">
                        @foreach($pedido->licencas as $licenca)
                            <div class="px-6 py-4 flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $licenca->produto?->nome ?? '—' }}</p>
                                    <p class="font-mono text-sm text-teal-600 mt-1">{{ $licenca->chave }}</p>
                                </div>

                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    {{ $licenca->status === 'ativa' ? 'bg-green-100 text-green-700' : ($licenca->status === 'cancelada' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $licenca->status_label }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-bold text-gray-800 mb-4">Resumo</h2>

                <dl class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-gray-500">Forma de pagamento</dt>
                        <dd class="text-gray-800 font-medium">{{ $pedido->metodo_pagamento_label }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-gray-500">Status do pagamento</dt>
                        <dd class="text-gray-800 font-medium">{{ $pedido->status_pagamento_label }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-gray-500">Pago em</dt>
                        <dd class="text-gray-800 font-medium">{{ $pedido->pago_em?->format('d/m/Y H:i') ?? '—' }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-gray-500">E-mail</dt>
                        <dd class="text-gray-800 font-medium truncate max-w-[180px]">{{ $pedido->email }}</dd>
                    </div>
                </dl>
            </div>

            @if($pedido->recorrente || $pedido->isRenovacao())
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 mb-4">Assinatura</h2>

                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Situação</dt>
                            <dd class="text-gray-800 font-medium">{{ $pedido->recorrencia_label }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Periodicidade</dt>
                            <dd class="text-gray-800 font-medium">{{ $pedido->billing_interval?->label() ?? '—' }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Ciclo</dt>
                            <dd class="text-gray-800 font-medium">{{ $pedido->ciclo }}</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-500">Próxima cobrança</dt>
                            <dd class="text-gray-800 font-medium">{{ $pedido->proximo_vencimento?->format('d/m/Y') ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            @endif

            @if($pedido->observacoes)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h2 class="font-bold text-gray-800 mb-3">Observações</h2>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $pedido->observacoes }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection
