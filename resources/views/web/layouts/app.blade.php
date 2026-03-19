<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="language" content="pt-br" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="copyright" content="{{$configuracoes->init_date}} - {{$configuracoes->app_name}}">
    <meta name="author" content="{{env('DESENVOLVEDOR')}}"/>
    <meta name="designer" content="Renato Montanari">
    <meta name="publisher" content="Renato Montanari">
    <meta name="url" content="{{ $configuracoes->domain }}" />
    <meta name="keywords" content="{{ $configuracoes->metatags }}">
    <meta name="distribution" content="web">
    <meta name="rating" content="general">
    <meta name="date" content="December 2018">

    {!! $head ?? '' !!}

    <link rel="icon" type="image/png" href="{{$configuracoes->getfaveicon()}}" />
    <link rel="shortcut icon" href="{{$configuracoes->getfaveicon()}}" type="image/x-icon"/> 

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">

    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/front.js'])

    @stack('styles')
</head>
<body 
    class="font-['Inter'] bg-white text-gray-800 antialiased" 
    x-data="cookieConsent">

    {{-- Header --}}
    @include('web.components.header')

    {{-- Conteúdo --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('web.components.footer')

    {{-- BANNER --}}
    <div 
        x-cloak
        x-show="!accepted"
        class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-40"
    >
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <p>
                Utilizamos cookies para melhorar sua experiência.
            </p>

            <div class="flex gap-3">
                <button @click="acceptAll()" class="bg-green-600 px-4 py-2 rounded">
                    Aceitar todos
                </button>

                <button @click="openModal()" class="bg-gray-600 px-4 py-2 rounded">
                    Preferências
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div 
        x-cloak
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        @click.self="closeModal()"
    >
        <div class="bg-white text-black p-6 rounded w-96 relative">
            
            <button 
                @click="closeModal()" 
                class="absolute top-2 right-2 text-gray-500"
            >
                ✕
            </button>

            <h2 class="text-lg font-bold mb-4">Preferências de Cookies</h2>

            <label class="block mb-2">
                <input type="checkbox" checked disabled>
                Essenciais
            </label>

            <label class="block mb-2">
                <input type="checkbox" x-model="stats">
                Estatísticos
            </label>

            <label class="block mb-4">
                <input type="checkbox" x-model="marketing">
                Marketing
            </label>

            <button 
                @click="save()" 
                class="bg-blue-600 text-white px-4 py-2 rounded w-full"
            >
                Salvar preferências
            </button>
        </div>
    </div>    

    @stack('scripts')

    <script>
        function shareWhatsApp(event) {
            event.preventDefault();

            const message = "Atendimento {{ $configuracoes->app_name }}";

            const phone = "{{ $configuracoes->whatsapp }}";

            const isMobile = /Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/i.test(navigator.userAgent);

            const whatsappUrl = isMobile
                ? `https://api.whatsapp.com/send?phone=${phone}&text=${message}`
                : `https://web.whatsapp.com/send?phone=${phone}&text=${message}`;

            window.open(whatsappUrl, '_blank');
        }
    </script>

</body>
</html>