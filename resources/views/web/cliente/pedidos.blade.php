@extends('web.cliente.layouts.app')

@section('title', 'Meus Pedidos - ' . $company->alias_name)

@section('content')

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Meus Pedidos</h1>
            <p class="text-gray-500 mt-1">Acompanhe suas compras, pagamentos e assinaturas.</p>
        </div>

        <a href="{{ route('web.produtos') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold transition">
            <i class="fas fa-store"></i> Ver sistemas
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('cliente.pedidos') }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ !request('status') ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
                Todos
            </a>
            <a href="{{ route('cliente.pedidos', ['status' => 'pendente']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ request('status') === 'pendente' ? 'bg-yellow-500 text-white' : 'bg-gray-100 text-gray-600 hover:bg-yellow-50 hover:text-yellow-600' }}">
                Aguardando pagamento
            </a>
            <a href="{{ route('cliente.pedidos', ['status' => 'pago']) }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ request('status') === 'pago' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-green-50 hover:text-green-600' }}">
                Pagos
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        @if($pedidos->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="text-left px-6 py-4 text-gray-500 font-medium">Pedido</th>
                            <th class="text-left px-6 py-4 text-gray-500 font-medium">Itens</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Data</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Total</th>
                            <th class="text-center px-6 py-4 text-gray-500 font-medium">Status</th>
                            <th class="text-right px-6 py-4 text-gray-500 font-medium">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pedidos as $pedido)
                            <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-gray-800">{{ $pedido->codigo }}</p>
                                    @if($pedido->recorrente)
                                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-blue-50 text-blue-600">
                                            <i class="fas fa-sync-alt text-[9px]"></i> {{ $pedido->billing_interval?->label() ?? 'Assinatura' }}
                                        </span>
                                    @elseif($pedido->isRenovacao())
                                        <span class="inline-flex mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-500">
                                            Renovação
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $pedido->itens->first()?->produto_nome ?? '—' }}
                                    <span class="block text-xs text-gray-400">{{ $pedido->itens->first()?->plano_nome }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-500">{{ $pedido->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-gray-800">{{ $pedido->total_formatado }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($pedido->status_pagamento === 'pago')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Pago</span>
                                    @elseif($pedido->status_pagamento === 'pendente')
                                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Pendente</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">Cancelado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('cliente.pedido', $pedido->codigo) }}"
                                        class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-xs font-medium hover:bg-teal-50 hover:text-teal-600 transition">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t">
                {{ $pedidos->links() }}
            </div>
        @else
            <div class="px-6 py-16 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-bag text-gray-300 text-2xl"></i>
                </div>
                <p class="text-gray-500">Você ainda não possui pedidos.</p>
                <a href="{{ route('web.produtos') }}" class="inline-block mt-4 text-teal-600 text-sm font-medium hover:underline">
                    Conhecer os sistemas <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        @endif
    </div>

@endsection
