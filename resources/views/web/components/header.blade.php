<header class="w-full bg-white shadow-sm sticky top-0 z-50">
    {{-- Top bar --}}
    <div class="bg-teal-700 text-white text-sm py-2 hidden md:block">

        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">

            <div class="flex items-center gap-6">
                @if ($configuracoes->email)
                    <a href="mailto:{{ $configuracoes->email }}" class="flex items-center gap-2 hover:text-teal-200 transition">
                        <i class="fas fa-envelope text-xs"></i>
                        {{ $configuracoes->email }}
                    </a>
                @endif
                
                @if ($configuracoes->whatsapp)
                    <a href="https://api.whatsapp.com/send?phone=5512991385030" target="_blank" class="flex items-center gap-2 hover:text-teal-200 transition">
                        <i class="fab fa-whatsapp text-xs"></i>
                        (12) 99138-5030
                    </a>
                @endif                
            </div>

            <div class="flex items-center gap-4">
                @if ($configuracoes->facebook)
                    <a href="{{ $configuracoes->facebook }}" target="_blank" class="hover:text-teal-200 transition"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if ($configuracoes->instagram)
                    <a href="{{ $configuracoes->instagram }}" target="_blank" class="hover:text-teal-200 transition"><i class="fab fa-instagram"></i></a>
                @endif
                @if ($configuracoes->linkedin)
                    <a href="{{ $configuracoes->linkedin }}" target="_blank" class="hover:text-teal-200 transition"><i class="fab fa-linkedin-in"></i></a>
                @endif
            </div>

        </div>
    </div>

    {{-- Nav --}}
    <nav x-data="{ open: false }" class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        {{-- Logo --}}
        <a href="{{ route('web.home') }}">
            <img src="{{ $configuracoes->getlogo() }}" alt="{{ $configuracoes->app_name }}" class="h-12">
        </a>

        {{-- Menu Desktop --}}
        <div class="hidden md:flex items-center gap-8">
            <a href="{{ route('web.home') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">Início</a>
            <a href="{{ route('web.portifolio') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">Nossos Trabalhos</a>
            <a href="{{ route('web.blog.artigos') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">Blog</a>
            <a href="{{ route('web.contato') }}" class="text-gray-700 hover:text-teal-600 font-medium transition">Atendimento</a>
            <a href="{{ route('web.contato') }}" class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 transition font-medium">
                Solicitar Orçamento
            </a>
        </div>

        {{-- Hamburger Mobile --}}
        <button @click="open = !open" class="md:hidden text-gray-700">
            <i class="fas fa-bars text-xl"></i>
        </button>

        {{-- Menu Mobile --}}
        <div x-show="open" x-transition
            class="absolute top-full left-0 w-full bg-white shadow-lg md:hidden border-t"
            @click.away="open = false"
        >
            <div class="flex flex-col px-4 py-4 gap-4">
                <a href="{{ route('web.home') }}" class="text-gray-700 hover:text-teal-600 font-medium">Início</a>
                <a href="{{ route('web.portifolio') }}" class="text-gray-700 hover:text-teal-600 font-medium">Nossos Trabalhos</a>
                <a href="{{ route('web.blog.artigos') }}" class="text-gray-700 hover:text-teal-600 font-medium">Blog</a>
                <a href="{{ route('web.contato') }}" class="text-gray-700 hover:text-teal-600 font-medium">Atendimento</a>
                <a href="{{ route('web.contato') }}" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-center font-medium">
                    Solicitar Orçamento
                </a>
            </div>
        </div>
    </nav>
</header>