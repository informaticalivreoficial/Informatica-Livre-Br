@extends('web.cliente.layouts.app')

@section('title', 'Minhas Licenças - ' . $company->alias_name)

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Minhas Licenças</h1>
        <p class="text-gray-500 mt-1">Chaves de acesso dos sistemas adquiridos.</p>
    </div>

    @if($licencas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($licencas as $licenca)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-start justify-between gap-4 mb-4">
                        <div>
                            <h2 class="font-bold text-gray-800">{{ $licenca->produto?->nome ?? 'Produto' }}</h2>
                            <a href="{{ route('cliente.pedido', $licenca->pedido?->codigo) }}" class="text-xs text-teal-600 hover:underline">
                                Pedido {{ $licenca->pedido?->codigo }}
                            </a>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $licenca->status === 'ativa' ? 'bg-green-100 text-green-700' : ($licenca->status === 'cancelada' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $licenca->status_label }}
                        </span>
                    </div>

                    <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Chave de licença</p>
                        <p class="font-mono text-sm text-gray-800 break-all">{{ $licenca->chave }}</p>
                    </div>

                    @if($licenca->url_sistema)
                        <p class="text-sm text-gray-500 mt-4">
                            <i class="fas fa-globe text-gray-400 mr-1"></i>
                            <a href="{{ $licenca->url_sistema }}" target="_blank" class="text-teal-600 hover:underline break-all">{{ $licenca->url_sistema }}</a>
                        </p>
                    @endif

                    @if($licenca->ativada_em)
                        <p class="text-xs text-gray-400 mt-3">Ativada em {{ $licenca->ativada_em->format('d/m/Y') }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $licencas->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm px-6 py-16 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-gray-300 text-2xl"></i>
            </div>
            <p class="text-gray-500">Nenhuma licença disponível ainda.</p>
            <a href="{{ route('cliente.pedidos') }}" class="inline-block mt-4 text-teal-600 text-sm font-medium hover:underline">
                Ver meus pedidos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    @endif

@endsection
