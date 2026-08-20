<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-file-invoice mr-2"></i> Relatório Financeiro</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.dashboard') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active">Relatórios</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex flex-col lg:flex-row lg:items-center gap-3">
        <div class="flex items-center gap-3 flex-wrap">
            <select wire:model.live="period" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="mensal">Mensal</option>
                <option value="trimestral">Trimestral</option>
                <option value="semestral">Semestral</option>
                <option value="anual">Anual</option>
            </select>
            <select wire:model.live="year" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                @foreach($years as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
            @if($period === 'mensal')
                <select wire:model.live="month" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    @foreach($data['months'] ?? [] as $num => $name)
                        <option value="{{ $num }}">{{ $name }}</option>
                    @endforeach
                </select>
            @elseif($period === 'trimestral')
                <select wire:model.live="quarter" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="1">1º Trimestre</option>
                    <option value="2">2º Trimestre</option>
                    <option value="3">3º Trimestre</option>
                    <option value="4">4º Trimestre</option>
                </select>
            @elseif($period === 'semestral')
                <select wire:model.live="half" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                    <option value="1">1º Semestre</option>
                    <option value="2">2º Semestre</option>
                </select>
            @endif
        </div>
        <div class="flex-1"></div>
        <a
            href="{{ $exportUrl }}"
            target="_blank"
            class="inline-flex items-center justify-center bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition shrink-0"
        >
            <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-up text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Receitas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($data['incomeTotal'], 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-arrow-down text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Despesas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($data['expenseTotal'], 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg {{ $data['balance'] >= 0 ? 'bg-blue-50 text-blue-600' : 'bg-red-50 text-red-600' }} flex items-center justify-center shrink-0">
                <i class="fas fa-balance-scale text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">R$ {{ number_format($data['balance'], 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-arrow-down text-red-500 mr-2"></i> Despesas por categoria
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Qtd.</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data['expenseByCategory'] as $row)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2.5 text-sm font-medium text-gray-800">{{ $row['category'] }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">{{ $row['count'] }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-red-600 text-right">R$ {{ number_format($row['amount'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">Nenhuma despesa no período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">
                    <i class="fas fa-arrow-up text-green-500 mr-2"></i> Receitas por categoria
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                            <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Qtd.</th>
                            <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($data['incomeByCategory'] as $row)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-2.5 text-sm font-medium text-gray-800">{{ $row['category'] }}</td>
                                <td class="px-4 py-2.5 text-sm text-gray-500">{{ $row['count'] }}</td>
                                <td class="px-4 py-2.5 text-sm font-semibold text-green-600 text-right">R$ {{ number_format($row['amount'], 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">Nenhuma receita no período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">
                <i class="fas fa-list mr-2 text-teal-600"></i> Lançamentos do período — {{ $data['label'] }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Mês</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Descrição</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($data['transactions'] as $transaction)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 text-sm text-gray-500">{{ $data['months'][$transaction->month] ?? $transaction->month }}</td>
                            <td class="px-4 py-2.5">
                                @if($transaction->type->value === 'expense')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Despesa</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Receita</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-sm font-medium text-gray-800">{{ $transaction->descriptionText() }}</td>
                            <td class="px-4 py-2.5 text-sm text-gray-500">{{ $transaction->category?->name ?? '—' }}</td>
                            <td class="px-4 py-2.5">
                                @if($transaction->status->value === 'paid')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Pago</span>
                                @elseif($transaction->status->value === 'received')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Recebido</span>
                                @elseif($transaction->status->value === 'cancelled')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Cancelado</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Pendente</span>
                                @endif
                            </td>
                            <td class="px-4 py-2.5 text-sm font-semibold text-gray-800 text-right">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-400">Nenhum lançamento no período.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>