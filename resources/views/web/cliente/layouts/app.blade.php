<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel do Cliente - Informática Livre')</title>
    <link rel="icon" href="{{ asset('theme/images/chave.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/front.js'])
    @stack('styles')

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-gray-800 antialiased">

    @php
        $clienteCompany = \App\Models\Company::find(session('cliente_company_id'));

        $clienteMenu = [
            ['route' => 'cliente.dashboard', 'pattern' => 'cliente.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-home'],
            ['route' => 'cliente.faturas', 'pattern' => 'cliente.faturas', 'label' => 'Faturas', 'icon' => 'fa-file-invoice'],
            ['route' => 'cliente.servicos', 'pattern' => 'cliente.servicos', 'label' => 'Serviços', 'icon' => 'fa-box'],
            ['route' => 'cliente.pedidos', 'pattern' => 'cliente.pedidos*', 'label' => 'Pedidos', 'icon' => 'fa-shopping-bag'],
            ['route' => 'cliente.licencas', 'pattern' => 'cliente.licencas', 'label' => 'Licenças', 'icon' => 'fa-key'],
            ['route' => 'cliente.empresa', 'pattern' => 'cliente.empresa', 'label' => 'Meus dados', 'icon' => 'fa-id-card'],
        ];
    @endphp

    <header x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="h-16 flex items-center justify-between gap-4">

                <a href="{{ route('cliente.dashboard') }}" class="shrink-0">
                    <img
                        src="{{ $configuracoes?->getlogo() ?? asset('theme/images/image.jpg') }}"
                        alt="{{ $configuracoes?->app_name ?? 'Informática Livre' }}"
                        class="h-9 w-auto max-w-[170px] object-contain"
                    >
                </a>

                <nav class="hidden lg:flex items-center gap-1">
                    @foreach ($clienteMenu as $item)
                        <a href="{{ route($item['route']) }}"
                            class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition
                                {{ request()->routeIs($item['pattern']) ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-teal-700' }}">
                            <i class="fas {{ $item['icon'] }} text-xs"></i>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="hidden lg:flex items-center gap-3 shrink-0">
                    <div class="w-px h-6 bg-gray-200"></div>

                    <div x-data="{ dropdown: false }" class="relative">
                        <button @click="dropdown = !dropdown"
                            class="flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-teal-700 transition">
                            <span class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-teal-700 text-xs"></i>
                            </span>
                            <span class="max-w-[140px] truncate">{{ $clienteCompany?->alias_name ?? 'Cliente' }}</span>
                            <i class="fas fa-chevron-down text-[10px] text-gray-400"></i>
                        </button>

                        <div x-show="dropdown"
                            x-cloak
                            x-transition
                            @click.away="dropdown = false"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 py-2">
                            <a href="{{ route('cliente.empresa') }}"
                                class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-teal-700 transition">
                                <i class="fas fa-id-card text-xs"></i> Meus dados
                            </a>

                            <form method="POST" action="{{ route('cliente.sair') }}" class="border-t border-gray-100 mt-1 pt-1">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition">
                                    <i class="fas fa-sign-out-alt text-xs"></i> Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <button @click="open = !open" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-50 transition">
                    <i x-show="!open" class="fas fa-bars text-lg"></i>
                    <i x-show="open" x-cloak class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-gray-100 bg-white"
            @click.away="open = false">
            <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">
                @foreach ($clienteMenu as $item)
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                            {{ request()->routeIs($item['pattern']) ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fas {{ $item['icon'] }} w-4 text-center"></i>
                        {{ $item['label'] }}
                    </a>
                @endforeach

                <div class="border-t border-gray-100 pt-3 mt-3">
                    <div class="flex items-center gap-3 px-4 py-2">
                        <span class="w-9 h-9 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-teal-700 text-xs"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-700 truncate">{{ $clienteCompany?->alias_name ?? 'Cliente' }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $clienteCompany?->email }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('cliente.sair') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt w-4 text-center"></i> Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-10">
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-gray-400">
            <span>{{ $configuracoes?->app_name ?? 'Informática Livre' }} · Painel do Cliente</span>
            <a href="{{ route('web.home') }}" class="hover:text-teal-600 transition">Voltar ao site</a>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
