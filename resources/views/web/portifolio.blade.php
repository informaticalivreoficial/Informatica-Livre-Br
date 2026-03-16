@extends('web.layouts.app')

@section('title', 'Portfólio - Informática Livre')
@section('description', 'Conheça nossos trabalhos e projetos desenvolvidos para clientes em Ubatuba/SP e região.')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Nosso Portfólio</h1>
            <p class="text-teal-100 text-lg max-w-xl mx-auto">Conheça alguns dos projetos que desenvolvemos para nossos clientes.</p>
        </div>
    </section>

    {{-- FILTROS --}}
    <section class="bg-white border-b sticky top-[73px] z-40">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-wrap gap-3 items-center">
            <a href="{{ route('site.portifolio') }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ !request('categoria') ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
                Todos
            </a>
            @foreach($categorias as $cat)
                @foreach($cat->children as $sub)
                    <a href="{{ route('site.portifolio', ['categoria' => $sub->id]) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition
                            {{ request('categoria') == $sub->id ? 'bg-teal-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
                        {{ $sub->title }}
                    </a>
                @endforeach
            @endforeach
        </div>
    </section>

    {{-- GRID --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            @if($trabalhos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trabalhos as $trabalho)
                        <a href="{{ route('site.portifolio.single', $trabalho->slug) }}"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                            <div class="overflow-hidden h-56">
                                @if($trabalho->cover)
                                    <img
                                        src="{{ Storage::url($trabalho->cover->path) }}"
                                        alt="{{ $trabalho->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                    >
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-300 text-4xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="text-xs text-teal-600 font-medium uppercase tracking-wide">
                                    {{ $trabalho->categoryRelation->title ?? '' }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800 mt-1 group-hover:text-teal-600 transition">
                                    {{ $trabalho->name }}
                                </h3>
                                @if($trabalho->headline)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $trabalho->headline }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-4">
                                    @if($trabalho->data_inicio)
                                        <span class="text-xs text-gray-400">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $trabalho->data_inicio->format('m/Y') }}
                                        </span>
                                    @endif
                                    <span class="text-teal-600 text-sm font-medium group-hover:translate-x-1 transition">
                                        Ver projeto <i class="fas fa-arrow-right ml-1"></i>
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginação --}}
                <div class="mt-12">
                    {{ $trabalhos->appends(request()->query())->links() }}
                </div>

            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-folder-open text-6xl mb-4"></i>
                    <p class="text-xl">Nenhum trabalho encontrado.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-700 to-teal-500">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Gostou do que viu?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e vamos criar algo incrível juntos.</p>
            <a href="{{ route('site.contato') }}"
                class="bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition inline-block">
                Solicitar Orçamento
            </a>
        </div>
    </section>

@endsection