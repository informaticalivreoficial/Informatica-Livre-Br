<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('description', 'Informática Livre - Desenvolvimento de sites e sistemas em Ubatuba/SP')">

    <title>@yield('title', 'Informática Livre')</title>

    <link rel="icon" href="{{ asset('theme/images/chave.png') }}" type="image/x-icon">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-['Inter'] bg-white text-gray-800 antialiased">

    {{-- Header --}}
    @include('web.components.header')

    {{-- Conteúdo --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('web.components.footer')

    @stack('scripts')
</body>
</html>