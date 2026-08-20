<div>
    @section('title', $title)

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-table mr-2"></i> Visão Anual Financeira</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('financial.dashboard') }}">Financeiro</a></li>
                        <li class="breadcrumb-item active">Visão Anual</li>
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

    @php
        $expenseColumnTotals = array_fill(1, 12, 0.0);
        foreach ($expenseRows as $row) {
            foreach ($row['monthly'] as $m => $v) {
                $expenseColumnTotals[$m] += $v;
            }
        }
        $incomeColumnTotals = array_fill(1, 12, 0.0);
        foreach ($incomeRows as $row) {
            foreach ($row['monthly'] as $m => $v) {
                $incomeColumnTotals[$m] += $v;
            }
        }
    @endphp

    @include('livewire.dashboard.financial.partials.annual-table', [
        'title' => 'DESPESAS',
        'icon' => 'fas fa-arrow-down',
        'iconColor' => 'text-red-500',
        'rows' => $expenseRows,
        'months' => $months,
        'columnTotals' => $expenseColumnTotals,
        'year' => $year,
    ])

    @include('livewire.dashboard.financial.partials.annual-table', [
        'title' => 'RECEITAS',
        'icon' => 'fas fa-arrow-up',
        'iconColor' => 'text-green-500',
        'rows' => $incomeRows,
        'months' => $months,
        'columnTotals' => $incomeColumnTotals,
        'year' => $year,
    ])

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total de despesas em {{ $year }}</p>
            <p class="text-xl font-bold text-red-600 leading-tight mt-1">R$ {{ number_format(array_sum($expenseColumnTotals), 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total de receitas em {{ $year }}</p>
            <p class="text-xl font-bold text-green-600 leading-tight mt-1">R$ {{ number_format(array_sum($incomeColumnTotals), 2, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Saldo anual</p>
            <p class="text-xl font-bold text-gray-800 leading-tight mt-1">R$ {{ number_format(array_sum($incomeColumnTotals) - array_sum($expenseColumnTotals), 2, ',', '.') }}</p>
        </div>
    </div>
</div>