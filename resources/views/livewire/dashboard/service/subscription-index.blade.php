<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Pedidos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Pedidos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-shopping-cart text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ativos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                <i class="fas fa-pause-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pausados</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['paused'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Cancelados</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['canceled'] }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="text"
                        wire:model.debounce.500ms="searchCompany"
                        placeholder="Buscar empresa..."
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    >
                </div>
                <div class="relative">
                    <i class="fas fa-cog absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="text"
                        wire:model.debounce.500ms="searchService"
                        placeholder="Buscar serviço..."
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    >
                </div>
                <select wire:model="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="">Todos os status</option>
                    <option value="active">Ativo</option>
                    <option value="paused">Pausado</option>
                    <option value="canceled">Cancelado</option>
                </select>
            </div>
            <a
                href="{{ route('services.subscriptions.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Pedido
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Empresa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Serviço</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Intervalo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Próx. cobrança</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $subscription->company->alias_name }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700">
                                {{ $subscription->service->name }}
                            </td>

                            <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                R$ {{ number_format($subscription->totalAmount(), 2, ',', '.') }}
                                @if ($subscription->items->count() > 0)
                                    <small class="block text-xs text-gray-400">
                                        + {{ $subscription->items->count() }} item(ns) extra(s)
                                    </small>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $subscription->interval?->label() ?? 'Único' }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-500">
                                {{ $subscription->next_billing_at?->format('d/m/Y') ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($subscription->status === \App\Enums\SubscriptionStatus::ACTIVE)
                                        bg-green-100 text-green-700
                                    @elseif($subscription->status === \App\Enums\SubscriptionStatus::PAUSED)
                                        bg-yellow-100 text-yellow-700
                                    @elseif($subscription->status === \App\Enums\SubscriptionStatus::CANCELED)
                                        bg-red-100 text-red-700
                                    @endif
                                ">
                                    {{ $subscription->status->label() }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('services.invoices.index', $subscription->id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Ver Faturas">
                                        <i class="fas fa-credit-card text-xs"></i>
                                    </a>
                                    <a href="{{ route('services.subscriptions.show', $subscription) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Visualizar">
                                        <i class="fas fa-search text-xs"></i>
                                    </a>
                                    <a href="{{ route('services.subscriptions.edit', $subscription) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button
                                        wire:click="confirmDelete({{ $subscription->id }})"
                                        type="button"
                                        title="Excluir"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum pedido encontrado</p>
                                <a href="{{ route('services.subscriptions.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro pedido
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($subscriptions->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $subscriptions->links() }}
            </div>
        @endif
    </div>
</div>