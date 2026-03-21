@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">Nossos Sistemas</h1>
            <p class="text-teal-100 text-lg max-w-2xl mx-auto">
                Sistemas prontos para uso, desenvolvidos com tecnologia moderna e instalados por nossa equipe.
            </p>
        </div>
    </section>

    {{-- PRODUTOS --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">

            @forelse($produtos as $produto)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-24 last:mb-0
                    {{ $loop->even ? 'lg:flex-row-reverse' : '' }}">

                    {{-- Imagem --}}
                    <div class="{{ $loop->even ? 'lg:order-2' : '' }}">
                        <div class="rounded-2xl overflow-hidden shadow-xl">
                            <img
                                src="{{ $produto->cover_url }}"
                                alt="{{ $produto->nome }}"
                                class="w-full object-cover"
                            >
                        </div>
                    </div>

                    {{-- Conteúdo --}}
                    <div class="{{ $loop->even ? 'lg:order-1' : '' }}">
                        <span class="inline-block bg-teal-50 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full mb-4">
                            Sistema Completo
                        </span>
                        <h2 class="text-3xl font-extrabold text-gray-800 mb-3">{{ $produto->nome }}</h2>

                        @if($produto->headline)
                            <p class="text-gray-500 text-lg mb-6">{{ $produto->headline }}</p>
                        @endif

                        {{-- Features --}}
                        @if($produto->features)
                            <ul class="space-y-2 mb-8">
                                @foreach(array_slice($produto->features, 0, 5) as $feature)
                                    <li class="flex items-center gap-2 text-gray-600 text-sm">
                                        <i class="fas fa-check-circle text-teal-500 flex-shrink-0"></i>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        {{-- Preço e botão --}}
                        <div class="flex items-center gap-4 flex-wrap">
                            @if($produto->menor_preco)
                                <div>
                                    <span class="text-xs text-gray-400">A partir de</span>
                                    <p class="text-3xl font-extrabold text-teal-600">
                                        R$ {{ number_format($produto->menor_preco, 2, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                            <a
                                href="{{ route('web.produto', $produto->slug) }}"
                                class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold transition"
                            >
                                Ver detalhes
                            </a>
                            @if($produto->demo_url)
                                <a
                                    href="{{ $produto->demo_url }}"
                                    target="_blank"
                                    class="border border-teal-600 text-teal-600 hover:bg-teal-50 px-6 py-3 rounded-lg font-semibold transition"
                                >
                                    <i class="fas fa-external-link-alt mr-2"></i>Ver demo
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Divisor entre produtos --}}
                @if(!$loop->last)
                    <hr class="border-gray-100 mb-24">
                @endif

            @empty
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-box-open text-5xl mb-4"></i>
                    <p class="text-lg">Nenhum produto disponível no momento.</p>
                </div>
            @endforelse

        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-700 to-teal-500">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Precisa de algo personalizado?</h2>
            <p class="text-teal-100 text-lg mb-8">Desenvolvemos sistemas sob medida para o seu negócio.</p>
            <a href="{{ route('web.contact') }}"
                class="bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition inline-block">
                Solicitar orçamento
            </a>
        </div>
    </section>

@endsection