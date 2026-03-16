@extends('web.cliente.layouts.app')

@section('title', 'Serviços - ' . $company->alias_name)

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Serviços Contratados</h1>
        <p class="text-gray-500 mt-1">Veja todos os serviços ativos e histórico.</p>
    </div>

    @if($servicos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($servicos as $servico)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-teal-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-box text-teal-600 text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">{{ $servico->service->name }}</h3>
                                @if($servico->service->description)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $servico->service->description }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $servico->status->value === 'active' ? 'bg-green-100 text-green-700' :
                               ($servico->status->value === 'paused' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $servico->status->label() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Valor</p>
                            <p class="font-semibold text-gray-800">
                                R$ {{ number_format($servico->amount, 2, ',', '.') }}
                                @if($servico->interval)
                                    <span class="text-gray-400 font-normal">/ {{ $servico->interval->label() }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Início</p>
                            <p class="font-medium text-gray-700">{{ $servico->start_date->format('d/m/Y') }}</p>
                        </div>
                        @if($servico->next_billing_at)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Próx. cobrança</p>
                                <p class="font-medium text-gray-700">{{ $servico->next_billing_at->format('d/m/Y') }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Faturas</p>
                            <p class="font-medium text-gray-700">{{ $servico->invoices->count() }} faturas</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm text-center py-16 text-gray-400">
            <i class="fas fa-box-open text-5xl mb-4"></i>
            <p class="text-lg">Nenhum serviço encontrado.</p>
        </div>
    @endif

@endsection