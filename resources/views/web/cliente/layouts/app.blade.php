<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Painel do Cliente - Informática Livre')</title>
    <link rel="icon" href="{{ asset('theme/images/chave.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-['Inter'] bg-gray-100 antialiased">

    <nav x-data="{ open: false }" class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('cliente.dashboard') }}">
                <img src="{{ asset('storage/configuracoes/logomarca-informatica-livre.png') }}" alt="Informática Livre" class="h-10">
            </a>

            {{-- Menu Desktop --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('cliente.dashboard') }}"
                    class="text-sm font-medium transition flex items-center gap-2
                        {{ request()->routeIs('cliente.dashboard') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('cliente.faturas') }}"
                    class="text-sm font-medium transition flex items-center gap-2
                        {{ request()->routeIs('cliente.faturas') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600' }}">
                    <i class="fas fa-file-invoice"></i> Faturas
                </a>
                <a href="{{ route('cliente.servicos') }}"
                    class="text-sm font-medium transition flex items-center gap-2
                        {{ request()->routeIs('cliente.servicos') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600' }}">
                    <i class="fas fa-box"></i> Serviços
                </a>
                <a href="{{ route('cliente.empresa') }}"
                    class="text-sm font-medium transition flex items-center gap-2
                        {{ request()->routeIs('cliente.empresa') ? 'text-teal-600' : 'text-gray-600 hover:text-teal-600' }}">
                    <i class="fas fa-building"></i> Empresa
                </a>

                {{-- Separador --}}
                <div class="w-px h-5 bg-gray-200"></div>

                {{-- Usuário --}}
                <div x-data="{ dropdown: false }" class="relative">
                    <button @click="dropdown = !dropdown"
                        class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-teal-600 transition">
                        <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-teal-600 text-xs"></i>
                        </div>
                        <span class="max-w-[120px] truncate">
                            {{ session('cliente_company_id') ? \App\Models\Company::find(session('cliente_company_id'))->alias_name : '' }}
                        </span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div x-show="dropdown"
                        x-transition
                        @click.away="dropdown = false"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2"
                    >
                        <form method="POST" action="{{ route('cliente.sair') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition flex items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Hamburger Mobile --}}
            <button @click="open = !open" class="md:hidden text-gray-600 hover:text-teal-600 transition">
                <i x-show="!open" class="fas fa-bars text-xl"></i>
                <i x-show="open" x-cloak class="fas fa-times text-xl"></i>
            </button>
        </div>

        {{-- Menu Mobile --}}
        <div x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t bg-white"
            @click.away="open = false"
        >
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('cliente.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                        {{ request()->routeIs('cliente.dashboard') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-home w-4"></i> Dashboard
                </a>
                <a href="{{ route('cliente.faturas') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                        {{ request()->routeIs('cliente.faturas') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-file-invoice w-4"></i> Faturas
                </a>
                <a href="{{ route('cliente.servicos') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                        {{ request()->routeIs('cliente.servicos') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-box w-4"></i> Serviços
                </a>
                <a href="{{ route('cliente.empresa') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition
                        {{ request()->routeIs('cliente.empresa') ? 'bg-teal-50 text-teal-600' : 'text-gray-600 hover:bg-gray-50' }}">
                    <i class="fas fa-building w-4"></i> Empresa
                </a>

                <div class="border-t pt-3 mt-3">
                    <div class="flex items-center gap-3 px-4 py-2 mb-2">
                        <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-teal-600 text-xs"></i>
                        </div>
                        <span class="text-sm font-medium text-gray-700 truncate">
                            {{ \App\Models\Company::find(session('cliente_company_id'))->alias_name ?? '' }}
                        </span>
                    </div>
                    <form method="POST" action="{{ route('cliente.sair') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-500 hover:bg-red-50 transition">
                            <i class="fas fa-sign-out-alt w-4"></i> Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Conteúdo --}}
    <main class="max-w-7xl mx-auto px-4 py-10">
        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>