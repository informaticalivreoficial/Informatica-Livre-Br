<div class="pb-10">
    @section('title', $title)

    <x-ui.page-header
        title="Loja"
        subtitle="Pedidos gerados pelo checkout do site"
        :breadcrumbs="['Painel de Controle' => route('admin'), 'Loja' => null, 'Pedidos' => null]"
    >
        <a
            href="{{ route('loja.licencas.index') }}"
            class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
        >
            <i class="fas fa-key text-xs"></i> Licenças
        </a>
    </x-ui.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-5">
        <x-ui.stat-card label="Pedidos" :value="$stats['total']" icon="fas fa-shopping-bag" tone="teal" />
        <x-ui.stat-card label="Aguardando pagamento" :value="$stats['pendentes']" icon="fas fa-clock" tone="amber" />
        <x-ui.stat-card label="Pagos" :value="$stats['pagos']" icon="fas fa-check-circle" tone="green" />
        <x-ui.stat-card label="Assinaturas ativas" :value="$stats['recorrentes']" icon="fas fa-sync-alt" tone="purple" />
        <x-ui.stat-card label="Receita confirmada" :value="$stats['receita']" icon="fas fa-coins" tone="blue" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Buscar por código, nome ou e-mail..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>

            <select
                wire:model.live="statusPagamento"
                class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            >
                <option value="">Todos os pagamentos</option>
                <option value="pendente">Aguardando pagamento</option>
                <option value="pago">Pago</option>
                <option value="cancelado">Cancelado</option>
                <option value="estornado">Estornado</option>
            </select>

            <select
                wire:model.live="status"
                class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            >
                <option value="">Todos os status</option>
                <option value="pendente">Pendente</option>
                <option value="confirmado">Confirmado</option>
                <option value="cancelado">Cancelado</option>
                <option value="reembolsado">Reembolsado</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Pedido</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cliente</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Itens</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Pagamento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($pedidos as $pedido)
                        <tr class="hover:bg-gray-50 transition" wire:key="pedido-{{ $pedido->id }}">
                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-800">{{ $pedido->codigo }}</p>
                                <p class="text-xs text-gray-400">{{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                                @if ($pedido->recorrente)
                                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-600">
                                        <i class="fas fa-sync-alt text-[9px]"></i> {{ $pedido->billing_interval?->label() ?? 'Recorrente' }}
                                    </span>
                                @elseif ($pedido->isRenovacao())
                                    <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-500">
                                        Renovação
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-800 truncate max-w-[220px]">{{ $pedido->nome }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[220px]">{{ $pedido->email }}</p>
                            </td>

                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                {{ $pedido->itens->count() }}
                            </td>

                            <td class="px-4 py-3">
                                <p class="text-sm font-semibold text-gray-800">{{ $pedido->total_formatado }}</p>
                                <p class="text-xs text-gray-400">{{ $pedido->metodo_pagamento_label }}</p>
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $pagamentoTone = match ($pedido->status_pagamento) {
                                        'pago' => 'bg-green-100 text-green-700',
                                        'pendente' => 'bg-amber-100 text-amber-700',
                                        'cancelado' => 'bg-gray-100 text-gray-600',
                                        'estornado' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $pagamentoTone }}">
                                    {{ $pedido->status_pagamento_label }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $statusTone = match ($pedido->status) {
                                        'confirmado' => 'bg-green-100 text-green-700',
                                        'pendente' => 'bg-amber-100 text-amber-700',
                                        'cancelado' => 'bg-red-100 text-red-700',
                                        'reembolsado' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusTone }}">
                                    {{ $pedido->status_label }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        wire:click="open({{ $pedido->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition"
                                        title="Ver detalhes"
                                    >
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                <i class="fas fa-shopping-bag text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum pedido encontrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pedidos->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>

    <div x-data="{ open: @entangle('showModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

            <div class="relative min-h-full flex items-start justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl my-8">
                    @if($pedido)
                        <div class="px-6 py-4 border-b border-gray-100 flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-base font-semibold text-gray-800">Pedido {{ $pedido->codigo }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Criado em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $pedido->status_pagamento === 'pago' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $pedido->status_pagamento_label }}
                                </span>

                                <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Cliente</p>
                                    <p class="text-gray-800 font-medium">{{ $pedido->nome }}</p>
                                    <p class="text-gray-500 text-xs">{{ $pedido->email }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Documento</p>
                                    <p class="text-gray-800 font-medium">{{ $pedido->cpf_cnpj ?: '—' }}</p>
                                    <p class="text-gray-500 text-xs">{{ $pedido->telefone ?: '—' }}</p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Pagamento</p>
                                    <p class="text-gray-800 font-medium">{{ $pedido->metodo_pagamento_label }}</p>
                                    <p class="text-gray-500 text-xs">
                                        {{ $pedido->pago_em ? 'Pago em ' . $pedido->pago_em->format('d/m/Y H:i') : 'Aguardando confirmação' }}
                                    </p>
                                </div>
                            </div>

                            <div class="overflow-hidden rounded-lg border border-gray-100">
                                <table class="min-w-full divide-y divide-gray-100">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Produto</th>
                                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Plano</th>
                                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Preço</th>
                                        </tr>
                                    </thead>

                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($pedido->itens as $item)
                                            <tr>
                                                <td class="px-4 py-2.5 text-sm text-gray-700">{{ $item->produto_nome }}</td>
                                                <td class="px-4 py-2.5 text-sm text-gray-500">
                                                    {{ $item->plano_nome }}
                                                    @if($item->plano?->isRecurring())
                                                        <span class="text-xs text-blue-600">({{ $item->plano->billingLabel() }})</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2.5 text-sm text-gray-800 text-right">{{ $item->preco_formatado }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-end gap-6 text-sm">
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Subtotal</p>
                                    <p class="text-gray-700">R$ {{ number_format((float) $pedido->subtotal, 2, ',', '.') }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Desconto</p>
                                    <p class="text-gray-700">R$ {{ number_format((float) $pedido->desconto, 2, ',', '.') }}</p>
                                </div>

                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Total</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $pedido->total_formatado }}</p>
                                </div>
                            </div>

                            @if($pedido->payment_url)
                                <div class="rounded-lg bg-teal-50 border border-teal-100 px-4 py-3 text-sm flex items-center justify-between gap-3">
                                    <span class="text-teal-700">
                                        <i class="fas fa-link mr-1"></i> Link de pagamento gerado no gateway
                                    </span>

                                    <a href="{{ $pedido->payment_url }}" target="_blank" class="text-teal-700 font-medium hover:underline">
                                        Abrir <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                </div>
                            @endif

                            @if($pedido->gateway_id)
                                <p class="text-xs text-gray-400">
                                    Transação no gateway: <span class="font-mono">{{ $pedido->gateway_id }}</span>
                                </p>
                            @endif

                            @if($pedido->recorrente)
                                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
                                    <div class="flex flex-wrap items-center justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Assinatura</p>
                                            <p class="text-sm text-gray-700 mt-1">
                                                {{ $pedido->billing_interval?->label() ?? 'Recorrente' }}
                                                · ciclo {{ $pedido->ciclo }}
                                                @if($pedido->proximo_vencimento)
                                                    · próxima cobrança em {{ $pedido->proximo_vencimento->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                            {{ $pedido->recorrencia_status?->value === 'ativa' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $pedido->recorrencia_label }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2 mt-3">
                                        @if($pedido->recorrencia_status?->value === 'ativa')
                                            <button
                                                wire:click="pausarRecorrencia({{ $pedido->id }})"
                                                class="inline-flex items-center gap-1.5 border border-gray-200 bg-white text-gray-600 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-50 transition"
                                            >
                                                <i class="fas fa-pause text-[10px]"></i> Pausar
                                            </button>
                                        @elseif($pedido->recorrencia_status?->value === 'pausada')
                                            <button
                                                wire:click="retomarRecorrencia({{ $pedido->id }})"
                                                class="inline-flex items-center gap-1.5 border border-green-200 bg-white text-green-600 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-green-50 transition"
                                            >
                                                <i class="fas fa-play text-[10px]"></i> Retomar
                                            </button>
                                        @endif

                                        @if($pedido->recorrencia_status?->value !== 'cancelada')
                                            <button
                                                wire:click="cancelarRecorrencia({{ $pedido->id }})"
                                                class="inline-flex items-center gap-1.5 border border-red-200 bg-white text-red-600 px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-red-50 transition"
                                            >
                                                <i class="fas fa-times text-[10px]"></i> Cancelar assinatura
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @elseif($pedido->isRenovacao())
                                <p class="text-xs text-gray-400">
                                    Renovação do pedido
                                    <a href="#" wire:click.prevent="open({{ $pedido->pedido_pai_id }})" class="text-teal-600 hover:underline">
                                        {{ $pedido->pai?->codigo }}
                                    </a>
                                </p>
                            @endif

                            @if($pedido->licencas->isNotEmpty())
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Licenças</p>

                                    <div class="space-y-2">
                                        @foreach($pedido->licencas as $licenca)
                                            <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-gray-100 px-3 py-2" wire:key="licenca-{{ $licenca->id }}">
                                                <div>
                                                    <p class="font-mono text-sm text-gray-800">{{ $licenca->chave }}</p>
                                                    <p class="text-xs text-gray-400">{{ $licenca->produto?->nome }}</p>
                                                </div>

                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                                    {{ $licenca->status === 'ativa' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ $licenca->status_label }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($pedido->observacoes)
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Observações</p>
                                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $pedido->observacoes }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 flex flex-wrap items-center justify-end gap-2">
                            @if($pedido->status_pagamento !== 'pago')
                                <button
                                    wire:click="gerarLinkPagamento({{ $pedido->id }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 border border-teal-200 text-teal-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-50 transition"
                                >
                                    <i class="fas fa-link text-xs"></i> Gerar link PagBank
                                </button>

                                <button
                                    wire:click="confirmPaid({{ $pedido->id }})"
                                    class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition"
                                >
                                    <i class="fas fa-check text-xs"></i> Confirmar pagamento
                                </button>
                            @else
                                <button
                                    wire:click="confirmRefund({{ $pedido->id }})"
                                    class="inline-flex items-center gap-2 border border-purple-200 text-purple-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-purple-50 transition"
                                >
                                    <i class="fas fa-rotate-left text-xs"></i> Reembolsar
                                </button>

                                <button
                                    wire:click="gerarLicencas({{ $pedido->id }})"
                                    class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
                                >
                                    <i class="fas fa-key text-xs"></i> Gerar licenças
                                </button>
                            @endif

                            @if(! in_array($pedido->status_pagamento, ['pago', 'cancelado']))
                                <button
                                    wire:click="confirmCancel({{ $pedido->id }})"
                                    class="inline-flex items-center gap-2 border border-red-200 text-red-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-red-50 transition"
                                >
                                    <i class="fas fa-ban text-xs"></i> Cancelar pedido
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
