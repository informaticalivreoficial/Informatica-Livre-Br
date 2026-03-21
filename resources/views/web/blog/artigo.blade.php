@extends('web.layouts.app')

@section('title', $post->title . ' - Informática Livre')
@section('description', $post->headline ?? strip_tags(substr($post->content, 0, 160)))

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-2 text-teal-200 text-sm mb-4">
                <a href="{{ route('web.home') }}" class="hover:text-white transition">Home</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('web.blog.artigos') }}" class="hover:text-white transition">Blog</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-white line-clamp-1">{{ $post->title }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3 max-w-4xl">{{ $post->title }}</h1>
            <div class="flex items-center gap-4 text-teal-100 text-sm mt-4">
                <span>
                    <i class="fas fa-calendar mr-1"></i>
                    {{ $post->publish_at
                        ? \Carbon\Carbon::parse($post->publish_at)->format('d/m/Y')
                        : $post->created_at->format('d/m/Y') }}
                </span>
                <span>
                    <i class="fas fa-eye mr-1"></i>
                    {{ $post->views }} visualizações
                </span>
            </div>
        </div>
    </section>

    {{-- CONTEÚDO --}}
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Artigo --}}
                <article class="lg:col-span-2">

                    {{-- Imagem de capa --}}                   
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-md">
                        <img
                            src="{{ $post->cover() }}"
                            alt="{{ $post->title }}"
                            class="w-full object-cover"
                        >
                    </div>                    

                    {{-- Conteúdo --}}
                    <div class="prose prose-gray prose-lg max-w-none text-gray-700 leading-relaxed">
                        {!! $post->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($post->tags)
                        <div class="mt-10 pt-6 border-t">
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-3">Tags</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $post->tags) as $tag)
                                    <span class="bg-teal-50 text-teal-700 text-xs px-3 py-1 rounded-full">
                                        {{ trim($tag) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Compartilhar --}}
                    @php
                        $url = urlencode(request()->fullUrl());
                        $title = urlencode($post->title);
                    @endphp
                    <div class="mt-10 pt-6 border-t">
                        <p class="text-sm font-medium text-gray-600 mb-3">Compartilhar:</p>
                        <div class="flex gap-3">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                                target="_blank"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition flex items-center gap-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a 
                                onclick="shareWhatsApp(event)"                            
                                target="_blank"
                                class="
                                bg-green-500 
                                text-white px-4 py-2 rounded-lg 
                                text-sm hover:bg-green-600 transition 
                                flex items-center gap-2 cursor-pointer"
                            >
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ $url }}&title={{ $title }}"
                                target="_blank"
                                class="bg-blue-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-800 transition flex items-center gap-2">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $url }}&text={{ $title }}"
                                target="_blank"
                                class="bg-blue-400 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-500 transition flex items-center gap-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://telegram.me/share/url?url={{ $url }}&text={{ $title }}"
                                target="_blank"
                                class="bg-blue-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-900 transition flex items-center gap-2">
                                <i class="fab fa-telegram-plane"></i>
                            </a>
                            <a href="mailto:?subject={{ $title }}&body={{ $url }}"
                                target="_blank"
                                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600 transition flex items-center gap-2">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Navegação --}}
                    <div class="mt-10 pt-6 border-t flex justify-between items-center">
                        <a href="{{ route('web.blog.artigos') }}" class="text-teal-600 hover:text-teal-700 font-medium transition flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Voltar ao Blog
                        </a>
                    </div>
                </article>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1 space-y-8">

                    {{-- Busca --}}
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Pesquisar</h3>
                        <form method="GET" action="{{ route('web.blog.artigos') }}" class="flex gap-2">
                            <input
                                type="text"
                                name="busca"
                                placeholder="Buscar..."
                                class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                            <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

                    {{-- Posts recentes --}}  
                    @if($recentes->count() > 0)
                        <div class="bg-gray-50 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Posts Recentes</h3>
                            <div class="space-y-4">
                                @foreach($recentes as $recente)
                                    <a href="{{ route('site.blog.single', $recente->slug) }}"
                                        class="flex gap-3 group">
                                        @if($recente->cover)
                                            <img
                                                src="{{ Storage::url($recente->cover->path) }}"
                                                alt="{{ $recente->title }}"
                                                class="w-16 h-16 object-cover rounded-lg flex-shrink-0"
                                            >
                                        @else
                                            <div class="w-16 h-16 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-newspaper text-teal-300"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-700 group-hover:text-teal-600 transition line-clamp-2">
                                                {{ $recente->title }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $recente->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- No sidebar, após posts recentes --}}
                    @if($post->categoryRelation)
                        <div class="bg-white rounded-2xl shadow-sm p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-4">Categoria</h3>
                            <a href="{{ route('site.blog.categoria', $post->categoryRelation->slug) }}"
                                class="inline-flex items-center gap-2 bg-teal-50 text-teal-700 px-4 py-2 rounded-full text-sm hover:bg-teal-100 transition">
                                <i class="fas fa-tag"></i>
                                {{ $post->categoryRelation->title }}
                            </a>
                        </div>
                    @endif

                    {{-- CTA sidebar --}}
                    <div class="bg-gradient-to-br from-teal-600 to-teal-800 rounded-2xl p-6 text-white text-center">
                        <i class="fas fa-laptop-code text-4xl mb-3 text-teal-200"></i>
                        <h3 class="font-bold text-lg mb-2">Precisa de um site?</h3>
                        <p class="text-teal-100 text-sm mb-4">Entre em contato e solicite um orçamento.</p>
                        <a href="{{ route('web.contact') }}"
                            class="bg-white text-teal-700 hover:bg-teal-50 px-6 py-2 rounded-lg font-medium text-sm transition inline-block">
                            Solicitar Orçamento
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        function shareWhatsApp(event) {
            event.preventDefault();

            const url = "{{ $url }}";
            const title = "{{ $title }}";

            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);

            const whatsappUrl = isMobile
                ? `https://api.whatsapp.com{title}%20${url}`
                : `https://web.whatsapp.com{title}%20${url}`;

            window.open(whatsappUrl, '_blank');
        }
    </script>
@endpush