@extends('web.layouts.app')

@section('content')

    {{-- HERO / SLIDER --}}
    <section x-data="{ current: 0, total: {{ $slides->count() ?: 1 }} }"
        x-init="setInterval(() => current = (current + 1) % total, 6000)"
        class="relative w-full overflow-hidden bg-gray-900"
        style="min-height: 560px;"
    >
        @forelse($slides as $index => $slide)
            <div
                x-show="current === {{ $index }}"
                x-transition:enter="transition-opacity duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0"
            >
                <img
                    src="{{ $slide->image ? Storage::url($slide->image) : asset('theme/images/image.jpg') }}"
                    alt="{{ $slide->title }}"
                    class="w-full h-full object-cover opacity-50"
                >
                <div class="absolute inset-0 bg-gradient-to-r from-gray-900/80 via-gray-900/40 to-transparent"></div>
                <div class="absolute inset-0 flex items-center">
                    <div class="max-w-7xl mx-auto px-4 w-full">
                        <div class="max-w-2xl text-white"
                            x-show="current === {{ $index }}"
                            x-transition:enter="transition-all duration-700 delay-300"
                            x-transition:enter-start="opacity-0 translate-y-8"
                            x-transition:enter-end="opacity-100 translate-y-0"
                        >
                            @if($slide->view_title)
                                <h1 class="text-4xl md:text-6xl font-extrabold mb-4 drop-shadow-lg leading-tight">
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
                                    class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-600 text-white px-8 py-3.5 rounded-lg font-semibold text-lg transition shadow-lg shadow-teal-500/30 hover:-translate-y-0.5">
                                    Saiba Mais <i class="fas fa-arrow-right text-sm"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="absolute inset-0 bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 flex items-center">
                <div class="max-w-7xl mx-auto px-4 w-full">
                    <div class="max-w-2xl text-white">
                        <span class="inline-block bg-white/10 text-teal-100 text-sm px-4 py-1.5 rounded-full mb-6 backdrop-blur">Desenvolvimento Web em Ubatuba/SP</span>
                        <h1 class="text-4xl md:text-6xl font-extrabold mb-4 leading-tight">Informática Livre</h1>
                        <p class="text-xl md:text-2xl mb-8 text-teal-100">Sites e sistemas sob medida para o seu negócio crescer online.</p>
                        <a href="{{ route('web.contact') }}" class="inline-flex items-center gap-2 bg-white text-teal-700 px-8 py-3.5 rounded-lg font-semibold text-lg hover:bg-teal-50 transition shadow-lg hover:-translate-y-0.5">
                            Solicitar Orçamento <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endforelse

        {{-- Dots --}}
        @if($slides->count() > 1)
            <div class="absolute bottom-5 left-0 w-full flex justify-center gap-2.5">
                @foreach($slides as $index => $slide)
                    <button
                        @click="current = {{ $index }}"
                        :class="current === {{ $index }} ? 'bg-teal-400 w-8' : 'bg-white/40 w-3 hover:bg-white/70'"
                        class="h-3 rounded-full transition-all duration-300"
                        :aria-label="'Slide {{ $index + 1 }}'"
                    ></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- STATS --}}
    <section class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center" x-data="counter({{ $stats['projetos'] ?? 0 }})">
                    <p class="text-4xl font-extrabold text-gradient" x-text="count"></p>
                    <p class="text-sm text-gray-500 mt-1">Projetos entregues</p>
                </div>
                <div class="text-center" x-data="counter({{ $stats['clientes'] ?? 0 }})">
                    <p class="text-4xl font-extrabold text-gradient" x-text="count"></p>
                    <p class="text-sm text-gray-500 mt-1">Clientes atendidos</p>
                </div>
                <div class="text-center" x-data="counter({{ $stats['posts'] ?? 0 }})">
                    <p class="text-4xl font-extrabold text-gradient" x-text="count"></p>
                    <p class="text-sm text-gray-500 mt-1">Artigos publicados</p>
                </div>
                <div class="text-center" x-data="counter({{ $stats['anos'] ?? 0 }})">
                    <p class="text-4xl font-extrabold text-gradient" x-text="count"></p>
                    <p class="text-sm text-gray-500 mt-1">Anos de experiência</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SERVIÇOS --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-14" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                <span class="inline-block bg-teal-100 text-teal-700 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4">Nossos serviços</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">O que fazemos</h2>
                <p class="text-gray-500 max-w-xl mx-auto">Soluções digitais completas para o seu negócio crescer online.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @php
                    $servicos = [
                        ['icon' => 'fa-laptop-code', 'title' => 'Criação de Sites', 'desc' => 'Sites modernos, rápidos e responsivos para sua empresa se destacar no digital.', 'color' => 'teal'],
                        ['icon' => 'fa-cogs', 'title' => 'Sistemas Web', 'desc' => 'Sistemas personalizados para automatizar e otimizar os processos da sua empresa.', 'color' => 'blue'],
                        ['icon' => 'fa-share-alt', 'title' => 'Redes Sociais', 'desc' => 'Conectamos seu site com as principais redes sociais para ampliar seu alcance.', 'color' => 'indigo'],
                    ];
                @endphp
                @foreach($servicos as $i => $servico)
                    <div x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700" style="transition-delay: {{ $i * 150 }}ms">
                        <div class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 h-full">
                            <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-teal-600 group-hover:scale-110 transition-all duration-300">
                                <i class="fas {{ $servico['icon'] }} text-teal-600 text-2xl group-hover:text-white transition-colors duration-300"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-2 text-gray-800">{{ $servico['title'] }}</h3>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $servico['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- PORTFÓLIO --}}
    @if($trabalhos->count() > 0)
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-14" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4">Portfólio</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nossos Trabalhos</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Conheça alguns dos projetos que desenvolvemos para nossos clientes.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($trabalhos as $i => $trabalho)
                        <a href="{{ route('web.portifolio.single', $trabalho->slug) }}"
                            x-data="reveal"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 block"
                            style="transition-delay: {{ $i * 100 }}ms">
                            <div class="overflow-hidden h-52 relative">
                                <img
                                    src="{{ $trabalho->cover() }}"
                                    alt="{{ $trabalho->name }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                >
                                <div class="absolute inset-0 bg-teal-600/0 group-hover:bg-teal-600/20 transition duration-300"></div>
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
                <div class="text-center mt-12" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                    <a href="{{ route('web.portifolio') }}" class="inline-flex items-center gap-2 border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-lg font-medium transition">
                        Ver todos os trabalhos <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CLIENTES --}}
    @if($clientes->count() > 0)
        <section class="py-16 bg-gray-50 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-10" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Nossos Clientes</h2>
                    <p class="text-gray-500">Empresas que confiam no nosso trabalho</p>
                </div>
            </div>
            <div class="relative">
                <div class="flex overflow-hidden">
                    <div class="flex items-center gap-16 animate-marquee shrink-0 pr-16">
                        @foreach($clientes as $cliente)
                            <div class="grayscale hover:grayscale-0 transition duration-300">
                                <img src="{{ $cliente->getlogo() }}" alt="{{ $cliente->alias_name }}" class="h-14 object-contain" title="{{ $cliente->alias_name }}">
                            </div>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-16 animate-marquee shrink-0 pr-16" aria-hidden="true">
                        @foreach($clientes as $cliente)
                            <div class="grayscale hover:grayscale-0 transition duration-300">
                                <img src="{{ $cliente->getlogo() }}" alt="{{ $cliente->alias_name }}" class="h-14 object-contain" title="{{ $cliente->alias_name }}">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- POSTS --}}
    @if($posts->count() > 0)
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-14" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                    <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4">Blog</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Dicas & Novidades</h2>
                    <p class="text-gray-500 max-w-xl mx-auto">Fique por dentro das últimas novidades do mundo digital.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($posts as $i => $post)
                        <a href="{{ route('web.blog.artigo', $post->slug) }}"
                            x-data="reveal"
                            :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                            class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 block"
                            style="transition-delay: {{ $i * 100 }}ms">
                            <div class="overflow-hidden h-48">
                                <img
                                    src="{{ $post->cover() }}"
                                    alt="{{ $post->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                >
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-gray-400 mb-2 flex items-center gap-3">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $post->publish_at ? \Carbon\Carbon::parse($post->publish_at)->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-eye mr-1"></i>{{ $post->views }}</span>
                                </p>
                                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-teal-600 transition line-clamp-2">
                                    {{ $post->title }}
                                </h3>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-12" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                    <a href="{{ route('web.blog.artigos') }}" class="inline-flex items-center gap-2 border-2 border-teal-600 text-teal-600 hover:bg-teal-600 hover:text-white px-8 py-3 rounded-lg font-medium transition">
                        Ver todos os posts <i class="fas fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </section>
    @endif

    {{-- CTA ORÇAMENTO --}}
    <section class="py-20 bg-gradient-to-r from-teal-800 via-teal-700 to-blue-800 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 text-center text-white relative" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
            <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-6 animate-float">
                <i class="fas fa-rocket text-3xl text-teal-200"></i>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para começar?</h2>
            <p class="text-teal-100 text-lg mb-8">Entre em contato e solicite um orçamento sem compromisso.</p>
            <a href="{{ route('web.contact') }}"
                class="inline-flex items-center gap-2 bg-white text-teal-700 hover:bg-teal-50 px-10 py-4 rounded-lg font-bold text-lg transition shadow-xl hover:-translate-y-1">
                Solicitar Orçamento <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

@endsection