@extends('web.cliente.layouts.app')

@section('title', 'Dashboard - ' . $company->alias_name)

@section('content')

    {{-- Boas vindas --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Olá, {{ $company->alias_name }}! 👋</h1>
        <p class="text-gray-500 mt-1">Bem-vindo ao seu painel de controle.</p>
    </div>

    {{-- Cards resumo --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-invoice text-teal-600 text-xl"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wide">Total</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalFaturas }}</p>
            <p class="text-sm text-gray-500 mt-1">Faturas</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wide">Pendentes</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $faturasAbertas }}</p>
            <p class="text-sm text-gray-500 mt-1">Em aberto</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wide">Pagas</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $faturasPagas }}</p>
            <p class="text-sm text-gray-500 mt-1">Faturas pagas</p>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-blue-500 text-xl"></i>
                </div>
                <span class="text-xs text-gray-400 uppercase tracking-wide">Ativos</span>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $totalServicos }}</p>
            <p class="text-sm text-gray-500 mt-1">Serviços ativos</p>
        </div>
    </div>

    {{-- Últimas faturas --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Últimas Faturas</h2>
            <a href="{{ route('cliente.faturas') }}" class="text-sm text-teal-600 hover:underline">
                Ver todas <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @php
            $ultimasFaturas = $company->invoices()
                ->with('subscription.service')
                ->orderByDesc('due_date')
                ->take(5)
                ->get();
        @endphp

        @if($ultimasFaturas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-gray-500 font-medium">Serviço</th>
                            <th class="text-center py-3 text-gray-500 font-medium">Vencimento</th>
                            <th class="text-center py-3 text-gray-500 font-medium">Valor</th>
                            <th class="text-center py-3 text-gray-500 font-medium">Status</th>
                            <th class="text-right py-3 text-gray-500 font-medium">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ultimasFaturas as $fatura)
                            <tr class="border-b last:border-0">
                                <td class="py-3 text-gray-700">
                                    {{ $fatura->subscription->service->name ?? '—' }}
                                </td>
                                <td class="py-3 text-center text-gray-600">
                                    {{ $fatura->due_date->format('d/m/Y') }}
                                </td>
                                <td class="py-3 text-center font-medium text-gray-800">
                                    R$ {{ number_format($fatura->amount, 2, ',', '.') }}
                                </td>
                                <td class="py-3 text-center">
                                    @if($fatura->status === 'paid')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Pago</span>
                                    @elseif($fatura->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Pendente</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Cancelado</span>
                                    @endif
                                </td>
                                <td class="py-3 text-right">
                                    @if($fatura->payment_url && $fatura->status === 'pending')
                                        <a href="{{ $fatura->payment_url }}" target="_blank"
                                            class="bg-teal-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-teal-700 transition">
                                            <i class="fas fa-barcode mr-1"></i> Pagar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-400 py-8">Nenhuma fatura encontrada.</p>
        @endif
    </div>

    {{-- Serviços ativos --}}
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Serviços Contratados</h2>
            <a href="{{ route('cliente.servicos') }}" class="text-sm text-teal-600 hover:underline">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @php
            $servicos = $company->subscriptions()->with('service')->active()->take(3)->get();
        @endphp

        @if($servicos->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($servicos as $servico)
                    <div class="border border-gray-100 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-teal-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800 text-sm">{{ $servico->service->name }}</p>
                                <span class="text-xs text-green-600 font-medium">Ativo</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            R$ {{ number_format($servico->amount, 2, ',', '.') }}
                            @if($servico->interval)
                                / {{ $servico->interval->label() }}
                            @endif
                        </p>
                        @if($servico->next_billing_at)
                            <p class="text-xs text-gray-400 mt-1">
                                <i class="fas fa-calendar mr-1"></i>
                                Próx. cobrança: {{ $servico->next_billing_at->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center text-gray-400 py-8">Nenhum serviço ativo.</p>
        @endif
    </div>

@endsection