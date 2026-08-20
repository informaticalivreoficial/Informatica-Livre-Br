<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-calendar-alt mr-2"></i> Lançamentos Financeiros</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.dashboard') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active">Lançamentos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-3">
            <select wire:model.live="year" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                @foreach($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            <select wire:model.live="month" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                @foreach($months as $num => $name)
                    <option value="{{ $num }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1"></div>
        <button
            wire:click="generateYear"
            wire:confirm="Gerar os lançamentos de {{ $year }}? Lançamentos já existentes não serão duplicados."
            class="inline-flex items-center justify-center bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shrink-0"
        >
            <i class="fas fa-magic mr-2"></i> Gerar lançamentos de {{ $year }}
        </button>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-up text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Receitas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($incomeTotal, 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-down text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Despesas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($expenseTotal, 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg {{ $incomeTotal - $expenseTotal >= 0 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center shrink-0">
                <i class="fas fa-scale-balanced text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($incomeTotal - $expenseTotal, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Despesas do mês --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-arrow-down text-red-500 mr-2"></i> Despesas de {{ $months[$month] }}/{{ $year }}
                </h3>
                <span class="text-xs text-gray-400">Pago: R$ {{ number_format($paidExpenses, 2, ',', '.') }} · Pendente: R$ {{ number_format($pendingExpenses, 2, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Descrição</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Venc.</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($expenses as $transaction)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2.5">
                                    <p class="text-sm font-medium text-gray-800">{{ $transaction->expense?->description ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $transaction->category?->name }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">
                                    @if($transaction->expense)
                                        {{ $transaction->dueDate()?->format('d/m') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($editingId === $transaction->id)
                                        <div class="flex items-center gap-1">
                                            <input
                                                type="text"
                                                wire:model="editingAmount"
                                                class="w-24 border border-teal-400 rounded-lg px-2 py-1 text-sm focus:outline-none"
                                                inputmode="decimal"
                                            >
                                            <button wire:click="saveAmount({{ $transaction->id }})" class="text-green-600 hover:text-green-700" title="Salvar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-600" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-sm font-medium text-gray-800">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($transaction->status === 'paid')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Pago</span>
                                    @elseif($transaction->status === 'cancelled')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Cancelado</span>
                                    @elseif($transaction->isOverdue())
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Vencido</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pendente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($editingId !== $transaction->id)
                                            <button wire:click="startEdit({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar valor">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status === 'pending')
                                            <button wire:click="markPaid({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Marcar como pago">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @elseif($transaction->status === 'paid')
                                            <button wire:click="undoPayment({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white transition" title="Desfazer pagamento">
                                                <i class="fas fa-undo text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status !== 'cancelled')
                                            <button wire:click="confirmCancel({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Cancelar lançamento">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <button wire:click="cancelTransaction({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Reativar lançamento">
                                                <i class="fas fa-rotate-left text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-400">Nenhuma despesa para este mês</p>
                                    <button wire:click="generateYear" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        <i class="fas fa-magic mr-1"></i> Gerar lançamentos de {{ $year }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Receitas do mês --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-arrow-up text-green-500 mr-2"></i> Receitas de {{ $months[$month] }}/{{ $year }}
                </h3>
                <span class="text-xs text-gray-400">Recebido: R$ {{ number_format($receivedIncomes, 2, ',', '.') }} · Pendente: R$ {{ number_format($pendingIncomes, 2, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Descrição</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Receb.</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($incomes as $transaction)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2.5">
                                    <p class="text-sm font-medium text-gray-800">{{ $transaction->income?->description ?? '—' }}</p>
                                    <p class="text-xs text-gray-400">{{ $transaction->category?->name }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">
                                    @if($transaction->income)
                                        {{ $transaction->receiveDate()?->format('d/m') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($editingId === $transaction->id)
                                        <div class="flex items-center gap-1">
                                            <input
                                                type="text"
                                                wire:model="editingAmount"
                                                class="w-24 border border-teal-400 rounded-lg px-2 py-1 text-sm focus:outline-none"
                                                inputmode="decimal"
                                            >
                                            <button wire:click="saveAmount({{ $transaction->id }})" class="text-green-600 hover:text-green-700" title="Salvar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button wire:click="cancelEdit" class="text-gray-400 hover:text-gray-600" title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-sm font-medium text-gray-800">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    @if($transaction->status === 'received')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Recebido</span>
                                    @elseif($transaction->status === 'cancelled')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Cancelado</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Esperado</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($editingId !== $transaction->id)
                                            <button wire:click="startEdit({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar valor">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status === 'pending')
                                            <button wire:click="markReceived({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Marcar como recebido">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @elseif($transaction->status === 'received')
                                            <button wire:click="undoReceipt({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white transition" title="Desfazer recebimento">
                                                <i class="fas fa-undo text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status !== 'cancelled')
                                            <button wire:click="confirmCancel({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Cancelar lançamento">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <button wire:click="cancelTransaction({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Reativar lançamento">
                                                <i class="fas fa-rotate-left text-xs"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-400">Nenhuma receita para este mês</p>
                                    <button wire:click="generateYear" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        <i class="fas fa-magic mr-1"></i> Gerar lançamentos de {{ $year }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>