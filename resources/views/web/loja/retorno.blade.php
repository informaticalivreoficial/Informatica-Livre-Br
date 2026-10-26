@extends('web.layouts.app')

@section('content')

    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-14">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('web.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <a href="{{ route('web.produtos') }}" class="hover:text-white transition">Sistemas</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white">Retorno do pagamento</span>
            </div>

            <h1 class="text-3xl md:text-4xl font-extrabold text-white">Obrigado pela compra</h1>
            <p class="text-teal-100 mt-2">Estamos verificando o retorno do PagBank.</p>
        </div>
    </section>

    <section class="py-14 bg-gray-50 min-h-[320px]">
        <div class="max-w-2xl mx-auto px-4">

            @if (! $pedido)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-search text-2xl"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Pedido não encontrado</h2>
                    <p class="text-gray-500 mb-6">
                        Não localizamos o pedido informado. Se você já realizou o pagamento, acompanhe a confirmação pelo e-mail cadastrado.
                    </p>

                    <a href="{{ route('web.produtos') }}" class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                        <i class="fas fa-arrow-left"></i> Voltar para os sistemas
                    </a>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center">
                    @if ($pedido->status_pagamento === 'pago')
                        <div class="w-16 h-16 rounded-full bg-green-100 text-green-600 flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-check text-2xl"></i>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pagamento confirmado!</h2>
                        <p class="text-gray-500 mb-6">
                            O pedido <strong class="text-gray-700">{{ $pedido->codigo }}</strong> foi pago com sucesso.
                            Os dados de acesso foram enviados para <strong class="text-gray-700">{{ $pedido->email }}</strong>.
                        </p>
                    @elseif ($pedido->status_pagamento === 'cancelado')
                        <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-times text-2xl"></i>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pagamento não concluído</h2>
                        <p class="text-gray-500 mb-6">
                            O pedido <strong class="text-gray-700">{{ $pedido->codigo }}</strong> foi cancelado ou recusado.
                            Você pode gerar uma nova cobrança pelo painel do cliente.
                        </p>
                    @else
                        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Aguardando confirmação</h2>
                        <p class="text-gray-500 mb-6">
                            O pedido <strong class="text-gray-700">{{ $pedido->codigo }}</strong> está aguardando a confirmação do PagBank.
                            Assim que o pagamento for aprovado, você receberá um e-mail com os dados de acesso.
                        </p>
                    @endif

                    <div class="rounded-xl bg-gray-50 border border-gray-100 p-5 text-left space-y-2 mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Pedido</span>
                            <span class="text-gray-800 font-medium">{{ $pedido->codigo }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Forma de pagamento</span>
                            <span class="text-gray-800 font-medium">{{ $pedido->metodo_pagamento_label }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Status</span>
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium
                                {{ $pedido->status_pagamento === 'pago' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $pedido->status_pagamento_label }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between border-t border-gray-200 pt-2">
                            <span class="text-gray-500 text-sm">Total</span>
                            <span class="text-lg font-bold text-gray-800">{{ $pedido->total_formatado }}</span>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if ($pedido->payment_url && ! in_array($pedido->status_pagamento, ['pago', 'cancelado']))
                            <a href="{{ $pedido->payment_url }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl font-semibold transition">
                                <i class="fas fa-credit-card"></i> Continuar pagamento
                            </a>
                        @endif

                        <a href="{{ route('cliente.entrar') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-gray-200 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50 transition">
                            <i class="fas fa-user"></i> Acessar painel do cliente
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </section>

@endsection
