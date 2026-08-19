@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 text-center text-white relative">
            <span class="inline-block bg-white/10 text-teal-100 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4 backdrop-blur">Portfólio</span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Nosso Portfólio</h1>
            <p class="text-teal-100 text-lg max-w-xl mx-auto">Conheça alguns dos projetos que desenvolvemos para nossos clientes.</p>
        </div>
    </section>

    {{-- FILTROS --}}
    <section class="bg-white border-b sticky top-[73px] z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-wrap gap-3 items-center justify-center">
            <a href="{{ route('web.portifolio') }}"
                class="px-4 py-2 rounded-full text-sm font-medium transition
                    {{ !request('categoria') ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/25' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
                Todos
            </a>
            @foreach($categorias as $cat)
                @foreach($cat->children as $sub)
                    <a href="{{ route('web.portifolio', ['categoria' => $sub->id]) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium transition
                            {{ request('categoria') == $sub->id ? 'bg-teal-600 text-white shadow-lg shadow-teal-600/25' : 'bg-gray-100 text-gray-600 hover:bg-teal-50 hover:text-teal-600' }}">
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
                    @foreach($trabalhos as $i => $trabalho)
                        <a href="{{ route('web.portifolio.single', $trabalho->slug) }}"
                            x-data="reveal"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 block"
                            style="transition-delay: {{ $i % 3 * 100 }}ms">
                            <div class="overflow-hidden h-56 relative">
                                <img
                                    src="{{ $trabalho->cover() }}"
                                    alt="{{ $trabalho->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                >
                                <div class="absolute inset-0 bg-teal-600/0 group-hover:bg-teal-600/20 transition duration-300"></div>
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
    <section class="py-20 bg-gradient-to-r from-teal-800 via-teal-700 to-blue-800 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 text-center text-white relative">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Gostou do que viu?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e vamos criar algo incrível juntos.</p>
            <a href="{{ route('web.contact') }}"
                class="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition shadow-xl hover:-translate-y-1">
                Solicitar Orçamento <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

@endsection