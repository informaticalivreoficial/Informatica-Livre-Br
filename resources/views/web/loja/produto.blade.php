@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('web.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('web.produtos') }}" class="hover:text-white transition">Sistemas</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">{{ $produto->nome }}</span>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4">{{ $produto->nome }}</h1>
                    @if($produto->headline)
                        <p class="text-teal-100 text-lg mb-6">{{ $produto->headline }}</p>
                    @endif
                    <div class="flex gap-3 flex-wrap">
                        @if($produto->menor_preco)
                            <div class="bg-white/10 rounded-xl px-5 py-3">
                                <span class="text-teal-200 text-xs block">A partir de</span>
                                <span class="text-white text-2xl font-extrabold">
                                    R$ {{ number_format($produto->menor_preco, 2, ',', '.') }}
                                </span>
                            </div>
                        @endif
                        @if($produto->demo_url)
                            <a href="{{ $produto->demo_url }}" target="_blank"
                                class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-xl transition font-medium">
                                <i class="fas fa-external-link-alt"></i> Ver demonstração
                            </a>
                        @endif
                    </div>
                </div>
                <div class="rounded-2xl overflow-hidden shadow-2xl">
                    <img src="{{ $produto->cover_url }}" alt="{{ $produto->nome }}" class="w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- CONTEÚDO PRINCIPAL --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Coluna principal --}}
                <div class="lg:col-span-2 space-y-16">

                    {{-- Descrição --}}
                    @if($produto->conteudo)
                        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                            {!! $produto->conteudo !!}
                        </div>
                    @endif

                    {{-- Funcionalidades --}}
                    @if($produto->features)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-8">O que está incluso</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($produto->features as $feature)
                                    <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                                        <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-check text-teal-600 text-xs"></i>
                                        </div>
                                        <span class="text-gray-700 text-sm">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Screenshots --}}
                    @if($produto->images->count() > 0)
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-8">Screenshots do sistema</h2>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($produto->images as $image)
                                    <div
                                        class="overflow-hidden rounded-xl cursor-pointer group"
                                        x-data
                                        @click="$dispatch('open-gallery', { index: {{ $loop->index }} })"
                                    >
                                        <img
                                            src="{{ $image->url }}"
                                            alt="{{ $produto->nome }}"
                                            class="w-full h-40 object-cover group-hover:scale-105 transition duration-500"
                                        >
                                    </div>
                                @endforeach
                            </div>

                            @livewire('web.image-gallery', [
                                'images' => $produto->images->map(fn($img) => ['path' => $img->path])->toArray()
                            ])
                        </div>
                    @endif

                </div>

                {{-- Sidebar: Planos --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-6">Escolha seu plano</h2>

                        @livewire('web.selecionar-plano', ['produto' => $produto])
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-700 to-teal-500">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl font-bold mb-4">Tem alguma dúvida?</h2>
            <p class="text-teal-100 text-lg mb-8">Fale com nossa equipe antes de comprar.</p>
            <a href="{{ route('web.contact') }}"
                class="bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition inline-block">
                Entrar em contato
            </a>
        </div>
    </section>

@endsection