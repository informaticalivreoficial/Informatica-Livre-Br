@extends('web.layouts.app')

@section('title', $trabalho->name . ' - Informática Livre')
@section('description', $trabalho->headline ?? $trabalho->name)

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 relative">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('web.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('web.portifolio') }}" class="hover:text-white transition">Portfólio</a>
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
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-lg" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                        <img
                            src="{{ $trabalho->cover() }}"
                            alt="{{ $trabalho->name }}"
                            class="w-full object-cover"
                        >
                    </div>

                    {{-- Descrição --}}
                    @if($trabalho->content)
                        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                            {!! $trabalho->content !!}
                        </div>
                    @endif

                    {{-- Galeria --}}
                    @if($trabalho->images->count() > 1)
                        <div class="mt-12">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                                <span class="w-1.5 h-6 bg-teal-500 rounded-full inline-block"></span>
                                Galeria de Imagens
                            </h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($trabalho->images as $image)
                                    <div
                                        class="overflow-hidden rounded-xl cursor-pointer group relative"
                                        x-data
                                        @click="$dispatch('open-gallery', { index: {{ $loop->index }} })"
                                    >
                                        <img
                                            src="{{ $image->url }}"
                                            alt="{{ $trabalho->name }}"
                                            class="w-full h-48 object-cover group-hover:scale-110 transition duration-500"
                                        >
                                        <div class="absolute inset-0 bg-teal-600/0 group-hover:bg-teal-600/30 flex items-center justify-center transition duration-300">
                                            <i class="fas fa-expand text-white text-xl opacity-0 group-hover:opacity-100 transition duration-300"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            @livewire('web.image-gallery', [
                                'images' => $trabalho->images->map(fn($img) => ['path' => $img->path])->toArray()
                            ])
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 space-y-5 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-teal-600"></i>
                            Detalhes do Projeto
                        </h3>

                        @if($trabalho->companyRelation)
                            <div class="flex items-start gap-3">
                                <span class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-building text-teal-600 text-xs"></i></span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Cliente</p>
                                    <p class="font-medium text-gray-700">{{ $trabalho->companyRelation->alias_name }}</p>
                                </div>
                            </div>
                        @endif

                        @if($trabalho->categoryRelation)
                            <div class="flex items-start gap-3">
                                <span class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-tag text-teal-600 text-xs"></i></span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Categoria</p>
                                    <p class="font-medium text-gray-700">{{ $trabalho->categoryRelation->title }}</p>
                                </div>
                            </div>
                        @endif

                        @if($trabalho->data_inicio)
                            <div class="flex items-start gap-3">
                                <span class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0"><i class="fas fa-calendar text-teal-600 text-xs"></i></span>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Período</p>
                                    <p class="font-medium text-gray-700">
                                        {{ $trabalho->data_inicio->format('m/Y') }}
                                        @if($trabalho->data_termino)
                                            — {{ $trabalho->data_termino->format('m/Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endif

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

                        @if($trabalho->link)
                            <div class="pt-2">
                                <a href="{{ $trabalho->link }}" target="_blank"
                                    class="w-full bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center justify-center gap-2 shadow-lg shadow-teal-600/25 hover:-translate-y-0.5">
                                    <i class="fas fa-external-link-alt"></i>
                                    Visitar Site
                                </a>
                            </div>
                        @endif

                        <div class="pt-2 text-center text-xs text-gray-400">
                            <i class="fas fa-eye mr-1"></i> {{ $trabalho->views }} visualizações
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-800 via-teal-700 to-blue-800 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 text-center text-white relative">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Quer um projeto como esse?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e vamos conversar sobre o seu projeto.</p>
            <a href="{{ route('web.contact') }}"
                class="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition shadow-xl hover:-translate-y-1">
                Solicitar Orçamento <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

@endsection