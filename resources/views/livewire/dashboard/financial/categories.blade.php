<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-tags mr-2"></i> Categorias Financeiras</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.dashboard') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active">Categorias</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-tags text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-down text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Despesas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['expenses'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-up text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Receitas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['incomes'] }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar categoria..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <select
                wire:model.live="type"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            >
                <option value="">Todos os tipos</option>
                <option value="expense">Despesas</option>
                <option value="income">Receitas</option>
            </select>
            <div class="flex items-center gap-2">
                <select
                    wire:model.live="budgetYear"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
                    @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                <select
                    wire:model.live="budgetMonth"
                    class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
                    @foreach($months as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <button
                wire:click="openCreate"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Nova Categoria
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Lançamentos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Orçamento</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-800">
                                    @if($category->icon)
                                        <i class="{{ $category->icon }} mr-1 text-gray-400"></i>
                                    @endif
                                    {{ $category->name }}
                                </p>
                            </td>
                            <td class="px-4 py-3">
                                @if($category->type === 'expense')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Despesa</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Receita</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-block w-6 h-6 rounded-full border border-gray-200" style="background-color: {{ $category->color }}"></span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $category->transactions_count }}</td>
                            <td class="px-4 py-3">
                                @if($category->type->value === 'expense' && $budgets->has($category->id))
                                    @php
                                        $budgetAmount = (float) $budgets[$category->id]->amount;
                                        $spentAmount = (float) ($spent[$category->id] ?? 0);
                                        $percent = $budgetAmount > 0 ? round(($spentAmount / $budgetAmount) * 100) : 0;
                                        $over = $spentAmount > $budgetAmount;
                                    @endphp
                                    <div class="min-w-[140px]">
                                        <div class="flex items-center justify-between text-xs mb-1">
                                            <span class="text-gray-500">R$ {{ number_format($spentAmount, 2, ',', '.') }} / R$ {{ number_format($budgetAmount, 2, ',', '.') }}</span>
                                            <span class="{{ $over ? 'text-red-600 font-semibold' : 'text-gray-400' }}">{{ $percent }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $over ? 'bg-red-500' : 'bg-teal-500' }}" style="width: {{ min($percent, 100) }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleActive({{ $category->id }})">
                                    @if($category->active)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($category->type->value === 'expense')
                                        <button
                                            wire:click="openBudget({{ $category->id }})"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition"
                                            title="Definir orçamento"
                                        >
                                            <i class="fas fa-coins text-xs"></i>
                                        </button>
                                    @endif
                                    <button
                                        wire:click="openEdit({{ $category->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Editar"
                                    >
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <button
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        wire:click="confirmDelete({{ $category->id }})"
                                        title="Excluir"
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
                                <p class="text-sm text-gray-400">Nenhuma categoria cadastrada</p>
                                <button wire:click="openCreate" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar a primeira categoria
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($categories->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    {{-- Modal de criação/edição --}}
    <div x-data="{ open: @entangle('showModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        {{ $editingId ? 'Editar Categoria' : 'Nova Categoria' }}
                    </h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input
                            type="text"
                            wire:model.defer="name"
                            placeholder="Ex.: Moradia"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('name') border-red-400 @enderror"
                        >
                        @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                        <select wire:model.defer="categoryType" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                            <option value="expense">Despesa</option>
                            <option value="income">Receita</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cor</label>
                            <input
                                type="color"
                                wire:model.defer="color"
                                class="w-full h-10 border border-gray-200 rounded-lg cursor-pointer"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ícone</label>
                            <input
                                type="text"
                                wire:model.defer="icon"
                                placeholder="fas fa-home"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model.defer="active" id="category-active" class="rounded border-gray-300">
                        <label for="category-active" class="text-sm text-gray-700">Ativa</label>
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

    {{-- Modal de orçamento --}}
    <div x-data="{ open: @entangle('showBudgetModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-black/50" @click="open = false"></div>
            <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Definir Orçamento</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveBudget" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mês *</label>
                            <select wire:model.defer="budgetMonth" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                @foreach($months as $num => $name)
                                    <option value="{{ $num }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ano *</label>
                            <select wire:model.defer="budgetYear" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                                @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valor do orçamento (R$) *</label>
                        <input
                            type="text"
                            wire:model.defer="budgetAmount"
                            placeholder="0,00"
                            inputmode="decimal"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('budgetAmount') border-red-400 @enderror"
                        >
                        @error('budgetAmount') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
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