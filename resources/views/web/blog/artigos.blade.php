@extends('web.layouts.app')

@section('title', 'Blog - Informática Livre')
@section('description', 'Dicas, novidades e tutoriais sobre tecnologia, desenvolvimento web e marketing digital.')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 text-center text-white relative">
            <span class="inline-block bg-white/10 text-teal-100 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4 backdrop-blur">Blog</span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Blog & Dicas</h1>
            <p class="text-teal-100 text-lg max-w-xl mx-auto">Fique por dentro das últimas novidades do mundo digital.</p>
        </div>
    </section>

    {{-- BUSCA --}}
    <section class="bg-white border-b sticky top-[73px] z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <form method="GET" action="{{ route('web.blog.artigos') }}" class="flex gap-3 max-w-md mx-auto">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input
                        type="text"
                        name="busca"
                        value="{{ request('busca') }}"
                        placeholder="Pesquisar posts..."
                        class="w-full border border-gray-200 rounded-lg pl-9 pr-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                    >
                </div>
                <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-teal-700 transition">
                    <i class="fas fa-search"></i>
                </button>
                @if(request('busca'))
                    <a href="{{ route('web.blog.artigos') }}" class="border border-gray-200 text-gray-500 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>
        </div>
    </section>

    {{-- POSTS --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $i => $post)
                        <a href="{{ route('web.blog.artigo', $post->slug) }}"
                            x-data="reveal"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 block"
                            style="transition-delay: {{ $i % 3 * 100 }}ms">

                            {{-- Imagem --}}
                            <div class="overflow-hidden h-52 relative">
                                <img
                                    src="{{ $post->cover() }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                >
                                <div class="absolute inset-0 bg-teal-600/0 group-hover:bg-teal-600/20 transition duration-300"></div>
                            </div>

                            {{-- Conteúdo --}}
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $post->publish_at
                                            ? \Carbon\Carbon::parse($post->publish_at)->format('d/m/Y')
                                            : $post->created_at->format('d/m/Y') }}
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $post->views }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-teal-600 transition line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                                @if($post->content)
                                    <p class="text-sm text-gray-500 mt-2 line-clamp-3">
                                        {{ strip_tags($post->content) }}
                                    </p>
                                @endif
                                <div class="mt-4 flex items-center text-teal-600 text-sm font-medium group-hover:translate-x-1 transition">
                                    Ler mais <i class="fas fa-arrow-right ml-2"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginação --}}
                <div class="mt-12">
                    {{ $posts->appends(request()->query())->links() }}
                </div>

            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-newspaper text-6xl mb-4"></i>
                    <p class="text-xl">
                        @if(request('busca'))
                            Nenhum post encontrado para "{{ request('busca') }}"
                        @else
                            Nenhum post encontrado.
                        @endif
                    </p>
                    @if(request('busca'))
                        <a href="{{ route('web.blog.artigos') }}" class="mt-4 inline-block text-teal-600 hover:underline">
                            Ver todos os posts
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 bg-gradient-to-r from-teal-800 via-teal-700 to-blue-800 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 text-center text-white relative">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Precisa de um site?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e solicite um orçamento sem compromisso.</p>
            <a href="{{ route('web.contact') }}"
                class="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition shadow-xl hover:-translate-y-1">
                Solicitar Orçamento <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

@endsection