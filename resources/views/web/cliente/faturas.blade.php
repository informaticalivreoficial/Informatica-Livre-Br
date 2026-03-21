@extends('web.cliente.layouts.app')

@section('title', 'Faturas - ' . $company->alias_name)

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Faturas</h1>
            <p class="text-gray-500 mt-1">Gerencie suas faturas e pagamentos.</p>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('cliente.faturas') }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ !request('status') ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
                Todas
            </a>
            <a href="{{ route('cliente.faturas', ['status' => 'pending']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-yellow-50 hover:text-yellow-600' }}">
                Pendentes
            </a>
            <a href="{{ route('cliente.faturas', ['status' => 'paid']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ request('status') === 'paid' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                Pagas
            </a>
        </div>
    </div>

    {{-- Tabela --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        @if($faturas->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-6 py-4 text-gray-500 font-medium">#</th>
                            <th class="text-left px-6 py-4 text-gray-500 font-medium">Serviço</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Vencimento</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Valor</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Status</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Pago em</th>
                            <th class="text-right px-6 py-4 text-gray-500 font-medium">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faturas as $fatura)
                            <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-400">#{{ $fatura->id }}</td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    {{ $fatura->subscription->service->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    {{ $fatura->due_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-800">
                                    R$ {{ number_format($fatura->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($fatura->status === 'paid')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Pago</span>
                                    @elseif($fatura->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Pendente</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Cancelado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-gray-500">
                                    {{ $fatura->paid_at ? $fatura->paid_at->format('d/m/Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($fatura->payment_url && $fatura->status === 'pending')
                                        <a href="{{ $fatura->payment_url }}" target="_blank"
                                            class="bg-teal-600 text-white px-4 py-2 rounded-lg text-xs hover:bg-teal-700 transition inline-flex items-center gap-1">
                                            <i class="fas fa-barcode"></i> Pagar Boleto
                                        </a>
                                    @elseif($fatura->status === 'paid')
                                        <span class="text-green-500 text-xs">
                                            <i class="fas fa-check-circle mr-1"></i> Pago
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $faturas->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-16 text-gray-400">
                <i class="fas fa-file-invoice text-5xl mb-4"></i>
                <p class="text-lg">Nenhuma fatura encontrada.</p>
            </div>
        @endif
    </div>

@endsection