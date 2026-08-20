<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-wallet mr-2"></i> Dashboard Financeiro</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Financeiro</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-4 flex items-center gap-3">
        <label class="text-sm font-medium text-gray-600">Ano:</label>
        <select wire:model.live="year" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
            @foreach($years as $y)
                <option value="{{ $y }}">{{ $y }}</option>
            @endforeach
        </select>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-sm p-4 text-white">
            <p class="text-xs font-medium text-green-100 uppercase tracking-wide">Receitas do mês</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($incomeTotal, 2, ',', '.') }}</p>
            <p class="text-xs text-green-100 mt-1">Recebido: R$ {{ number_format($receivedInMonth, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-sm p-4 text-white">
            <p class="text-xs font-medium text-red-100 uppercase tracking-wide">Despesas do mês</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($expenseTotal, 2, ',', '.') }}</p>
            <p class="text-xs text-red-100 mt-1">Pago: R$ {{ number_format($paidInMonth, 2, ',', '.') }}</p>
        </div>
        <div class="bg-gradient-to-br {{ $balance >= 0 ? 'from-blue-500 to-indigo-600' : 'from-gray-600 to-gray-800' }} rounded-xl shadow-sm p-4 text-white">
            <p class="text-xs font-medium text-blue-100 uppercase tracking-wide">Saldo do mês</p>
            <p class="text-2xl font-bold mt-1">R$ {{ number_format($balance, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-clock text-sm"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $indicators['pendingExpenses'] }}</p>
                    <p class="text-xs text-gray-500">Despesas pendentes</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-exclamation-triangle text-sm"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $indicators['overdueExpenses'] }}</p>
                    <p class="text-xs text-gray-500">Despesas vencidas</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-check-circle text-sm"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $indicators['paidExpenses'] }}</p>
                    <p class="text-xs text-gray-500">Despesas pagas</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-hourglass-half text-sm"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $indicators['expectedIncomes'] }}</p>
                    <p class="text-xs text-gray-500">Receitas esperadas</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                    <i class="fas fa-hand-holding-usd text-sm"></i>
                </div>
                <div>
                    <p class="text-lg font-bold text-gray-800 leading-tight">{{ $indicators['receivedIncomes'] }}</p>
                    <p class="text-xs text-gray-500">Receitas recebidas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-800">
                <i class="fas fa-chart-bar text-teal-600 mr-2"></i> Comparação anual — {{ $year }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Mês</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-green-600 uppercase tracking-wide">Receitas</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-red-600 uppercase tracking-wide">Despesas</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($annualComparison as $month => $data)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2.5 font-medium text-gray-800">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</td>
                            <td class="px-4 py-2.5 text-right text-green-600">R$ {{ number_format($data['income'], 2, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-right text-red-600">R$ {{ number_format($data['expense'], 2, ',', '.') }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold {{ $data['income'] - $data['expense'] >= 0 ? 'text-gray-800' : 'text-red-600' }}">
                                R$ {{ number_format($data['income'] - $data['expense'], 2, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>