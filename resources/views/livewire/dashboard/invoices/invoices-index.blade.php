<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-money-check mr-2"></i> Faturas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('services.subscriptions.index') }}">Pedidos</a></li>
                        <li class="breadcrumb-item active">Faturas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                <i class="fas fa-file-invoice text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-100 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 dark:bg-green-900/40 text-green-600 dark:text-green-400 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wide">Pagas</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-100 leading-tight">{{ $stats['paid'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-yellow-50 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400 flex items-center justify-center shrink-0">
                <i class="fas fa-clock text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wide">Pendentes</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-100 leading-tight">{{ $stats['pending'] }}</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center shrink-0">
                <i class="fas fa-exclamation-triangle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wide">Em atraso</p>
                <p class="text-xl font-bold text-gray-800 dark:text-slate-100 leading-tight">{{ $stats['overdue'] }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <div class="p-4 border-b border-gray-100 dark:border-slate-700 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Buscar empresa ou ID da fatura..."
                    class="w-full border border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <select wire:model.live="status" class="w-full lg:w-56 border border-gray-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">Todos os status</option>
                <option value="pending">Pendente</option>
                <option value="paid">Pago</option>
                <option value="canceled">Cancelado</option>
                <option value="refunded">Estornado</option>
            </select>
            <a
                href="{{ route('invoices.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-teal-600 text-white text-sm font-medium hover:bg-teal-700 transition"
            >
                <i class="fas fa-plus text-xs"></i> Nova fatura
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700">
                <thead class="bg-gray-50 dark:bg-slate-700/50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Empresa</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Vencimento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Valor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-slate-400 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-700">
                    @forelse ($invoices as $invoice)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-slate-400">#{{ $invoice->id }}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-slate-200">
                                <div>
                                    {{ $invoice->company?->alias_name ?? $invoice->payer_name ?? '—' }}
                                    @if ($invoice->isCustomPayer())
                                        <span class="ml-1 px-2 py-0.5 rounded text-[10px] font-semibold uppercase bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">Avulso</span>
                                    @endif
                                </div>
                                @if ($invoice->description)
                                    <div class="text-xs text-gray-400 dark:text-slate-500 truncate max-w-[240px]">{{ $invoice->description }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 dark:text-slate-400">{{ $invoice->due_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800 dark:text-slate-100">R$ {{ number_format($invoice->amount, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                    @if($invoice->status === 'paid') bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400
                                    @elseif($invoice->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900/40 dark:text-yellow-400
                                    @elseif($invoice->status === 'canceled') bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400
                                    @elseif($invoice->status === 'refunded') bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-300
                                    @endif
                                ">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($invoice->gateway_reference)
                                        <button
                                            wire:click="syncInvoice({{ $invoice->id }})"
                                            wire:loading.attr="disabled"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition"
                                            title="Atualizar Status"
                                        >
                                            <i class="fas fa-sync text-xs"></i>
                                        </button>
                                    @endif

                                    <a
                                        href="{{ route('invoices.show', $invoice) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                        title="Visualizar"
                                    >
                                        <i class="fas fa-search text-xs"></i>
                                    </a>

                                    <a
                                        href="{{ route('invoices.duplicate', $invoice) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white transition"
                                        title="Duplicar fatura"
                                    >
                                        <i class="fas fa-copy text-xs"></i>
                                    </a>

                                    @if ($invoice->status !== 'paid' && $invoice->due_date->isFuture())
                                        <button
                                            wire:click="generateBoleto({{ $invoice->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="generateBoleto({{ $invoice->id }})"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                            title="Gerar Boleto"
                                        >
                                            <span wire:loading.remove wire:target="generateBoleto({{ $invoice->id }})">
                                                <i class="fas fa-barcode text-xs"></i>
                                            </span>
                                            <span wire:loading wire:target="generateBoleto({{ $invoice->id }})">
                                                <i class="fas fa-spinner fa-spin text-xs"></i>
                                            </span>
                                        </button>
                                    @endif

                                    @if (
                                        $invoice->status === 'pending' ||
                                        $invoice->status === 'canceled' ||
                                        $invoice->status === 'failed')
                                        <button
                                            wire:click="markAsPaid({{ $invoice->id }})"
                                            wire:confirm="Confirmar pagamento da fatura #{{ $invoice->id }}?"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition"
                                            title="Marcar como Pago"
                                        >
                                            <i class="fas fa-check text-xs"></i>
                                        </button>
                                    @endif

                                    <button
                                        wire:click="confirmDelete({{ $invoice->id }})"
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
                            <td colspan="6" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 dark:text-slate-600 mb-3"></i>
                                <p class="text-sm text-gray-400 dark:text-slate-500">Nenhuma fatura encontrada</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 dark:border-slate-700">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('openUrl', ({ url }) => {
                window.open(url, '_blank');
            });
        });
    </script>
@endpush