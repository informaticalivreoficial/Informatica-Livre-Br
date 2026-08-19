@extends('web.layouts.app')

@section('title', $categoria->title . ' - Blog Informática Livre')
@section('description', 'Artigos sobre ' . $categoria->title . ' - Informática Livre')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 text-center text-white relative">
            <div class="flex items-center justify-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('site.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('site.blog') }}" class="hover:text-white transition">Blog</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white">{{ $categoria->title }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">{{ $categoria->title }}</h1>
            @if($categoria->content)
                <p class="text-teal-100 text-lg max-w-xl mx-auto">{{ $categoria->content }}</p>
            @endif
        </div>
    </section>

    {{-- POSTS --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $i => $post)
                        <a href="{{ route('site.blog.single', $post->slug) }}"
                            x-data="reveal"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 block"
                            style="transition-delay: {{ $i % 3 * 100 }}ms">

                            {{-- Imagem --}}
                            <div class="overflow-hidden h-52">
                                @if($post->cover)
                                    <img
                                        src="{{ Storage::url($post->cover->path) }}"
                                        alt="{{ $post->title }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                    >
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-teal-50 to-teal-100 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-teal-300 text-5xl"></i>
                                    </div>
                                @endif
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
                    {{ $posts->links() }}
                </div>

            @else
                <div class="text-center py-20 text-gray-400">
                    <i class="fas fa-newspaper text-6xl mb-4"></i>
                    <p class="text-xl">Nenhum post encontrado nesta categoria.</p>
                    <a href="{{ route('site.blog') }}" class="mt-4 inline-block text-teal-600 hover:underline">
                        Ver todos os posts
                    </a>
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
            <a href="{{ route('site.contato') }}"
                class="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition shadow-xl hover:-translate-y-1">
                Solicitar Orçamento <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

@endsection