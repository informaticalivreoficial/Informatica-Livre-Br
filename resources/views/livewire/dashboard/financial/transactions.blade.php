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
            <select wire:model.live="statusFilter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">Todos os status</option>
                <option value="pending">Pendente</option>
                <option value="paid">Pago</option>
                <option value="received">Recebido</option>
                <option value="cancelled">Cancelado</option>
            </select>
        </div>
        <div class="flex-1"></div>
        <button
            wire:click="confirmGenerateMonth"
            class="inline-flex items-center justify-center bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shrink-0"
        >
            <i class="fas fa-magic mr-2"></i> Gerar mês
        </button>
        <button
            wire:click="confirmGenerateYear"
            class="inline-flex items-center justify-center bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shrink-0"
        >
            <i class="fas fa-calendar-plus mr-2"></i> Gerar {{ $year }}
        </button>
        <button
            wire:click="exportCsv"
            class="inline-flex items-center justify-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition shrink-0"
        >
            <i class="fas fa-file-csv mr-2"></i> Exportar CSV
        </button>
        <div class="flex items-center gap-2 shrink-0">
            <button
                wire:click="openCreate('expense')"
                class="inline-flex items-center justify-center bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition"
            >
                <i class="fas fa-arrow-down mr-2"></i> Despesa
            </button>
            <button
                wire:click="openCreate('income')"
                class="inline-flex items-center justify-center bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700 transition"
            >
                <i class="fas fa-arrow-up mr-2"></i> Receita
            </button>
        </div>
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
                <i class="fas fa-balance-scale text-lg"></i>
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
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $transaction->descriptionText() }}
                                        @if($transaction->payment_method)
                                            <span class="ml-1 text-xs text-gray-400"><i class="fas fa-credit-card mr-0.5"></i>{{ $transaction->paymentMethodLabel() }}</span>
                                        @endif
                                        @if($transaction->attachment_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($transaction->attachment_path) }}" target="_blank" class="ml-1 text-teal-600 hover:text-teal-700" title="Comprovante">
                                                <i class="fas fa-paperclip text-xs"></i>
                                            </a>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $transaction->category?->name }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">
                                    @if($transaction->dueDate())
                                        {{ $transaction->dueDate()->format('d/m') }}
                                        @if($transaction->status->value === 'pending')
                                            @php $days = $transaction->daysUntilDue(); @endphp
                                            @if($days < 0)
                                                <span class="ml-1 text-xs font-medium text-red-600">há {{ abs($days) }}d</span>
                                            @elseif($days === 0)
                                                <span class="ml-1 text-xs font-medium text-red-600">hoje</span>
                                            @elseif($days <= 3)
                                                <span class="ml-1 text-xs font-medium text-yellow-600">em {{ $days }}d</span>
                                            @endif
                                        @endif
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
                                    @if($transaction->status->value === 'paid')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Pago</span>
                                    @elseif($transaction->status->value === 'cancelled')
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
                                        <button wire:click="openEdit({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Editar lançamento">
                                            <i class="fas fa-sliders-h text-xs"></i>
                                        </button>
                                        @if($transaction->status->value === 'pending')
                                            <button wire:click="markPaid({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Marcar como pago">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @elseif($transaction->status->value === 'paid')
                                            <button wire:click="undoPayment({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white transition" title="Desfazer pagamento">
                                                <i class="fas fa-undo text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status->value !== 'cancelled')
                                            <button wire:click="confirmCancel({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Cancelar lançamento">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <button wire:click="cancelTransaction({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Reativar lançamento">
                                                <i class="fas fa-rotate-left text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->isAdHoc())
                                            <button wire:click="confirmDelete({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Excluir lançamento">
                                                <i class="fas fa-trash text-xs"></i>
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
                                    <button wire:click="confirmGenerateMonth" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        <i class="fas fa-magic mr-1"></i> Gerar lançamentos de {{ $months[$month] }}
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
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $transaction->descriptionText() }}
                                        @if($transaction->payment_method)
                                            <span class="ml-1 text-xs text-gray-400"><i class="fas fa-credit-card mr-0.5"></i>{{ $transaction->paymentMethodLabel() }}</span>
                                        @endif
                                        @if($transaction->attachment_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($transaction->attachment_path) }}" target="_blank" class="ml-1 text-teal-600 hover:text-teal-700" title="Comprovante">
                                                <i class="fas fa-paperclip text-xs"></i>
                                            </a>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $transaction->category?->name }}</p>
                                </td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">
                                    @if($transaction->receiveDate())
                                        {{ $transaction->receiveDate()->format('d/m') }}
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
                                    @if($transaction->status->value === 'received')
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Recebido</span>
                                    @elseif($transaction->status->value === 'cancelled')
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
                                        <button wire:click="openEdit({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="Editar lançamento">
                                            <i class="fas fa-sliders-h text-xs"></i>
                                        </button>
                                        @if($transaction->status->value === 'pending')
                                            <button wire:click="markReceived({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition" title="Marcar como recebido">
                                                <i class="fas fa-check text-xs"></i>
                                            </button>
                                        @elseif($transaction->status->value === 'received')
                                            <button wire:click="undoReceipt({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-600 hover:text-white transition" title="Desfazer recebimento">
                                                <i class="fas fa-undo text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->status->value !== 'cancelled')
                                            <button wire:click="confirmCancel({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Cancelar lançamento">
                                                <i class="fas fa-ban text-xs"></i>
                                            </button>
                                        @else
                                            <button wire:click="cancelTransaction({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Reativar lançamento">
                                                <i class="fas fa-rotate-left text-xs"></i>
                                            </button>
                                        @endif
                                        @if($transaction->isAdHoc())
                                            <button wire:click="confirmDelete({{ $transaction->id }})" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition" title="Excluir lançamento">
                                                <i class="fas fa-trash text-xs"></i>
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
                                    <button wire:click="confirmGenerateMonth" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                        <i class="fas fa-magic mr-1"></i> Gerar lançamentos de {{ $months[$month] }}
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal de criação/edição --}}
    <div x-data="{ open: @entangle('showForm') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $formId ? 'Editar Lançamento' : 'Novo Lançamento' }}
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select
                                wire:model.defer="formType"
                                @if($formId) disabled @endif
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @if($formId) bg-gray-100 text-gray-500 @endif"
                            >
                                <option value="expense">Despesa</option>
                                <option value="income">Receita</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria *</label>
                            <select wire:model.defer="formCategoryId" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('formCategoryId') border-red-400 @enderror">
                                <option value="">Selecione...</option>
                                @foreach($categories as $category)
                                    @if($category->type->value === $formType)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('formCategoryId') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição *</label>
                        <input
                            type="text"
                            wire:model.defer="formDescription"
                            placeholder="Ex.: Compra de material de escritório"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('formDescription') border-red-400 @enderror"
                        >
                        @error('formDescription') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Valor (R$) *</label>
                            <input
                                type="text"
                                wire:model.defer="formAmount"
                                placeholder="0,00"
                                inputmode="decimal"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('formAmount') border-red-400 @enderror"
                            >
                            @error('formAmount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Método de pagamento</label>
                            <select wire:model.defer="formPaymentMethod" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                <option value="">Selecione...</option>
                                <option value="pix">Pix</option>
                                <option value="boleto">Boleto</option>
                                <option value="cartao">Cartão</option>
                                <option value="transferencia">Transferência</option>
                                <option value="dinheiro">Dinheiro</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mês *</label>
                            <select wire:model.defer="formMonth" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ano *</label>
                            <input
                                type="number"
                                wire:model.defer="formYear"
                                min="2000"
                                max="2100"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Vencimento / Recebimento</label>
                            <input
                                type="date"
                                wire:model.defer="formDueDate"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data efetiva (pago/recebido)</label>
                            <input
                                type="date"
                                wire:model.defer="formEffectiveDate"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                            <p class="text-xs text-gray-400 mt-1">Preencha para marcar como pago/recebido.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Comprovante</label>
                        <input
                            type="file"
                            wire:model="attachment"
                            accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100"
                        >
                        @error('attachment') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        @if($attachmentPath)
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-paperclip mr-1"></i>
                                Comprovante atual: {{ basename($attachmentPath) }}
                            </p>
                        @endif
                        <div wire:loading wire:target="attachment" class="text-xs text-teal-600 mt-1">
                            <i class="fas fa-spinner fa-spin mr-1"></i> Enviando arquivo...
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition">
                            <i class="fas fa-save mr-1"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>