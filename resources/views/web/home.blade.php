@extends('web.layouts.app')

@section('content')

    {{-- HERO / SLIDER --}}
    <section x-data="{ current: 0, total: {{ $slides->count() ?: 1 }} }" 
        x-init="setInterval(() => current = (current + 1) % total, 5000)"
        class="relative w-full overflow-hidden bg-gray-900"
        style="min-height: 520px;"
    >
        @forelse($slides as $index => $slide)
            <div 
                x-show="current === {{ $index }}"
                x-transition:enter="transition-opacity duration-700"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-700"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
            >
                <img 
                    src="{{ $slide->image ? Storage::url($slide->image) : asset('theme/images/image.jpg') }}" 
                    alt="{{ $slide->title }}"
                    class="w-full h-full object-cover opacity-60"
                >
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-white px-4">
                        @if($slide->view_title)
                            <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg">
                                {{ $slide->title }}
                            </h1>
                        @endif
                        @if($slide->content)
                            <p class="text-lg md:text-2xl mb-8 text-gray-200 drop-shadow">
                                {{ $slide->content }}
                            </p>
                        @endif
                        @if($slide->link)
                            <a href="{{ $slide->link }}" 
                                target="{{ $slide->target ? '_blank' : '_self' }}"
                                class="bg-teal-500 hover:bg-teal-600 text-white px-8 py-3 rounded-lg font-semibold text-lg transition">
                                Saiba Mais
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="absolute inset-0 bg-gradient-to-r from-teal-800 to-teal-600 flex items-center justify-center">
                <div class="text-center text-white px-4">
                    <h1 class="text-4xl md:text-6xl font-extrabold mb-4">Informática Livre</h1>
                    <p class="text-xl md:text-2xl mb-8 text-teal-100">Desenvolvimento de sites e sistemas em Ubatuba/SP</p>
                    <a href="{{ route('web.contato') }}" class="bg-white text-teal-700 px-8 py-3 rounded-lg font-semibold text-lg hover:bg-teal-50 transition">
                        Solicitar Orçamento
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Dots --}}
        @if($slides->count() > 1)
            <div class="absolute bottom-4 left-0 w-full flex justify-center gap-2">
                @foreach($slides as $index => $slide)
                    <button 
                        @click="current = {{ $index }}"
                        :class="current === {{ $index }} ? 'bg-white' : 'bg-white/40'"
                        class="w-3 h-3 rounded-full transition"
                    ></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- SERVIÇOS --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">O que fazemos</h2>
                <p class="text-gray-500 max-w-xl mx-auto">Soluções digitais completas para o seu negócio crescer online.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-laptop-code text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Criação de Sites</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sites modernos, rápidos e responsivos para sua empresa se destacar no digital.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-cogs text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Sistemas Web</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Sistemas personalizados para automatizar e otimizar os processos da sua empresa.</p>
                </div>
                <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-md transition text-center">
                    <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-share-alt text-teal-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Redes Sociais</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">Conectamos seu site com as principais redes sociais para ampliar seu alcance.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- PORTFÓLIO --}}
    @if($trabalhos->count() > 0)
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nossos Trabalhos</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Conheça alguns dos projetos que desenvolvemos para nossos clientes.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trabalhos as $trabalho)
                        <a href="{{ route('web.portifolio.single', $trabalho->slug) }}" 
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                            <div class="overflow-hidden h-52">                                
                                <img 
                                    src="{{ $trabalho->cover() }}" 
                                    alt="{{ $trabalho->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >                                
                            </div>
                            <div class="p-5">
                                <span class="text-xs text-teal-600 font-medium uppercase tracking-wide">
                                    {{ $trabalho->categoryRelation->title ?? '' }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800 mt-1 group-hover:text-teal-600 transition">
                                    {{ $trabalho->name }}
                                </h3>
                                @if($trabalho->headline)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $trabalho->headline }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('web.portifolio') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-lg font-medium transition">
                        Ver todos os trabalhos
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CLIENTES --}}
    @if($clientes->count() > 0)
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Nossos Clientes</h2>
                    <p class="text-gray-500">Empresas que confiam no nosso trabalho</p>
                </div>
                <div class="flex flex-wrap justify-center items-center gap-8">
                    @foreach($clientes as $cliente)                        
                        <div class="grayscale hover:grayscale-0 transition duration-300">
                            <img 
                                src="{{ $cliente->getlogo() }}" 
                                alt="{{ $cliente->alias_name }}"
                                class="h-14 object-contain"
                                title="{{ $cliente->alias_name }}"
                            >
                        </div>                        
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- POSTS --}}
    @if($posts->count() > 0)
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Dicas & Novidades</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Fique por dentro das últimas novidades do mundo digital.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <a href="{{ route('web.blog.artigo', $post->slug) }}" 
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                            
                            <div class="overflow-hidden h-48">
                                <img 
                                    src="{{ $post->cover() }}" 
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                >
                            </div>
                            
                            <div class="p-5">
                                <p class="text-xs text-gray-400 mb-2">
                                    {{ $post->publish_at ? \Carbon\Carbon::parse($post->publish_at)->format('d/m/Y') : $post->created_at->format('d/m/Y') }}
                                </p>
                                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-teal-600 transition line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-10">
                    <a href="{{ route('web.blog.artigos') }}" class="border border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-lg font-medium transition">
                        Ver todos os posts
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA ORÇAMENTO --}}
    <section class="py-20 bg-gradient-to-r from-teal-700 to-teal-500">
        <div class="max-w-4xl mx-auto px-4 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para começar?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e solicite um orçamento sem compromisso.</p>
            <a href="" 
                class="bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition inline-block">
                Solicitar Orçamento
            </a>
        </div>
    </section>

@endsection