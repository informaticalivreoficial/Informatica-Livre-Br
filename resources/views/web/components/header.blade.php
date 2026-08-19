<header class="w-full sticky top-0 z-50">
    {{-- Top bar --}}
    <div class="bg-gradient-to-r from-teal-800 via-teal-700 to-teal-600 text-white text-sm py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-6">
                @if ($configuracoes->email)
                    <a href="mailto:{{ $configuracoes->email }}" class="flex items-center gap-2 hover:text-teal-200 transition group">
                        <i class="fas fa-envelope text-xs group-hover:scale-110 transition"></i>
                        {{ $configuracoes->email }}
                    </a>
                @endif

                @if ($configuracoes->whatsapp)
                    <a href="https://api.whatsapp.com/send?phone=5512991385030" target="_blank" class="flex items-center gap-2 hover:text-teal-200 transition group">
                        <i class="fab fa-whatsapp text-xs group-hover:scale-110 transition"></i>
                        (12) 99138-5030
                    </a>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <span class="text-teal-200/80 text-xs hidden lg:inline">Siga-nos:</span>
                @if ($configuracoes->facebook)
                    <a href="{{ $configuracoes->facebook }}" target="_blank" class="hover:text-teal-200 transition hover:scale-110 inline-block"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if ($configuracoes->instagram)
                    <a href="{{ $configuracoes->instagram }}" target="_blank" class="hover:text-teal-200 transition hover:scale-110 inline-block"><i class="fab fa-instagram"></i></a>
                @endif
                @if ($configuracoes->linkedin)
                    <a href="{{ $configuracoes->linkedin }}" target="_blank" class="hover:text-teal-200 transition hover:scale-110 inline-block"><i class="fab fa-linkedin-in"></i></a>
                @endif
            </div>
        </div>
    </div>

    {{-- Nav --}}
    <nav x-data="{ open: false, scrolled: false }"
        x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 10, { passive: true })"
        :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-md' : 'bg-white shadow-sm'"
        class="transition-all duration-300"
    >
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Logo --}}
            <a href="{{ route('web.home') }}" class="flex items-center gap-2 group">
                <img src="{{ $configuracoes->getlogo() }}" alt="{{ $configuracoes->app_name }}" class="h-11 transition-transform duration-300 group-hover:scale-105">
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center gap-7">
                @php
                    $menuItems = [
                        ['label' => 'Início', 'route' => 'web.home'],
                        ['label' => 'Nossos Trabalhos', 'route' => 'web.portifolio'],
                        ['label' => 'Blog', 'route' => 'web.blog.artigos'],
                        ['label' => 'Sistemas', 'route' => 'web.produtos'],
                        ['label' => 'Atendimento', 'route' => 'web.contact'],
                    ];
                @endphp
                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                        class="relative text-gray-700 hover:text-teal-600 font-medium transition group py-1
                            {{ request()->routeIs($item['route']) ? 'text-teal-600' : '' }}">
                        {{ $item['label'] }}
                        <span class="absolute left-0 -bottom-0.5 h-0.5 w-0 bg-teal-600 transition-all duration-300 group-hover:w-full
                            {{ request()->routeIs($item['route']) ? 'w-full' : '' }}"></span>
                    </a>
                @endforeach
                <a href="{{ route('web.contact') }}" class="bg-teal-600 text-white px-5 py-2.5 rounded-lg hover:bg-teal-700 transition font-medium shadow-lg shadow-teal-600/25 hover:shadow-teal-600/40 hover:-translate-y-0.5 active:translate-y-0">
                    Solicitar Orçamento
                </a>
            </div>

            {{-- Hamburger Mobile --}}
            <button @click="open = !open" class="md:hidden text-gray-700 p-2 hover:bg-gray-100 rounded-lg transition" aria-label="Menu">
                <i class="fas fa-bars text-xl" x-show="!open"></i>
                <i class="fas fa-times text-xl" x-show="open" x-cloak></i>
            </button>
        </div>

        {{-- Menu Mobile --}}
        <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            x-cloak
            class="md:hidden bg-white border-t shadow-lg"
            @click.away="open = false"
        >
            <div class="flex flex-col px-4 py-4 gap-1">
                @foreach($menuItems as $item)
                    <a href="{{ route($item['route']) }}"
                        @click="open = false"
                        class="flex items-center justify-between px-4 py-3 rounded-lg text-gray-700 font-medium transition
                            {{ request()->routeIs($item['route']) ? 'bg-teal-50 text-teal-700' : 'hover:bg-gray-50' }}">
                        {{ $item['label'] }}
                        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                    </a>
                @endforeach
                <a href="{{ route('web.contact') }}" @click="open = false" class="mt-2 bg-teal-600 text-white px-5 py-3 rounded-lg text-center font-medium hover:bg-teal-700 transition">
                    Solicitar Orçamento
                </a>
            </div>
        </div>
    </nav>
</header>