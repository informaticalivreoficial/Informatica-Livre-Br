@extends('web.layouts.app')

@section('title', $trabalho->name . ' - Informática Livre')
@section('description', $trabalho->headline ?? $trabalho->name)

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('site.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('site.portifolio') }}" class="hover:text-white transition">Portfólio</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">{{ $trabalho->name }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3">{{ $trabalho->name }}</h1>
            @if($trabalho->headline)
                <p class="text-teal-100 text-lg max-w-2xl">{{ $trabalho->headline }}</p>
            @endif
        </div>
    </section>

    {{-- CONTEÚDO --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Conteúdo Principal --}}
                <div class="lg:col-span-2">

                    {{-- Imagem de capa --}}
                    @if($trabalho->cover)
                        <div class="rounded-2xl overflow-hidden mb-8 shadow-md">
                            <img
                                src="{{ Storage::url($trabalho->cover->path) }}"
                                alt="{{ $trabalho->name }}"
                                class="w-full object-cover"
                            >
                        </div>
                    @endif

                    {{-- Descrição --}}
                    @if($trabalho->content)
                        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                            {!! $trabalho->content !!}
                        </div>
                    @endif

                    {{-- Galeria --}}
                    @if($trabalho->images->count() > 1)
                        <div class="mt-12">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6">Galeria de Imagens</h2>
                            <div 
                                x-data="{ lightbox: false, current: '' }"
                                class="grid grid-cols-2 md:grid-cols-3 gap-4"
                            >
                                @foreach($trabalho->images as $image)
                                    <div 
                                        class="overflow-hidden rounded-xl cursor-pointer group"
                                        @click="lightbox = true; current = '{{ Storage::url($image->path) }}'"
                                    >
                                        <img
                                            src="{{ Storage::url($image->path) }}"
                                            alt="{{ $trabalho->name }}"
                                            class="w-full h-48 object-cover group-hover:scale-105 transition duration-500"
                                        >
                                    </div>
                                @endforeach

                                {{-- Lightbox --}}
                                <div 
                                    x-show="lightbox"
                                    x-cloak
                                    x-transition
                                    class="fixed inset-0 bg-black/80 z-[9999] flex items-center justify-center p-4"
                                    @click.self="lightbox = false"
                                    @keydown.escape.window="lightbox = false"
                                >
                                    <div class="relative max-w-5xl w-full">
                                        <img :src="current" class="w-full max-h-[80vh] object-contain rounded-xl">
                                        <button 
                                            @click="lightbox = false"
                                            class="absolute -top-4 -right-4 bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg hover:bg-gray-100 transition"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 space-y-5">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-3">Detalhes do Projeto</h3>

                        {{-- Cliente --}}
                        @if($trabalho->company)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Cliente</p>
                                <p class="font-medium text-gray-700">{{ $trabalho->company->alias_name }}</p>
                            </div>
                        @endif

                        {{-- Categoria --}}
                        @if($trabalho->categoryRelation)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Categoria</p>
                                <p class="font-medium text-gray-700">{{ $trabalho->categoryRelation->title }}</p>
                            </div>
                        @endif

                        {{-- Período --}}
                        @if($trabalho->data_inicio)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Período</p>
                                <p class="font-medium text-gray-700">
                                    {{ $trabalho->data_inicio->format('m/Y') }}
                                    @if($trabalho->data_termino)
                                        — {{ $trabalho->data_termino->format('m/Y') }}
                                    @endif
                                </p>
                            </div>
                        @endif

                        {{-- Valor --}}
                        @if($trabalho->value)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Investimento</p>
                                <p class="font-medium text-gray-700">
                                    R$ {{ number_format($trabalho->value, 2, ',', '.') }}
                                </p>
                            </div>
                        @endif

                        {{-- Tags --}}
                        @if($trabalho->tags)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Tags</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $trabalho->tags) as $tag)
                                        <span class="bg-teal-50 text-teal-700 text-xs px-3 py-1 rounded-full">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Link --}}
                        @if($trabalho->link)
                            <div class="pt-2">
                                <a href="{{ $trabalho->link }}" target="_blank"
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center gap-2">
                                    <i class="fas fa-external-link-alt"></i>
                                    Visitar Site
                                </a>
                            </div>
                        @endif

                        {{-- Views --}}
                        <div class="pt-2 text-center text-xs text-gray-400">
                            <i class="fas fa-eye mr-1"></i> {{ $trabalho->views }} visualizações
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-700 to-teal-500">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Quer um projeto como esse?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e vamos conversar sobre o seu projeto.</p>
            <a href="{{ route('site.contato') }}"
                class="bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition inline-block">
                Solicitar Orçamento
            </a>
        </div>
    </section>

@endsection