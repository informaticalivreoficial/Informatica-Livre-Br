<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Erro') - Informatica Livre BR</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">

    @vite(['resources/css/app.css'])
</head>
<body class="font-['Inter'] antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-teal-50 flex items-center justify-center p-6">
        <div class="w-full max-w-lg text-center">
            <p class="text-8xl font-extrabold bg-gradient-to-r from-teal-600 to-blue-600 bg-clip-text text-transparent leading-none select-none">
                @yield('code')
            </p>

            <div class="mt-8 inline-flex items-center justify-center w-16 h-16 rounded-2xl @yield('iconBg') text-2xl">
                <i class="@yield('icon')"></i>
            </div>

            <h1 class="mt-5 text-2xl font-bold text-gray-800">
                @yield('title')
            </h1>

            <p class="mt-2 text-gray-500 leading-relaxed">
                @yield('message')
            </p>

            <div class="mt-8 flex items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-teal-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                    <i class="fas fa-home text-xs"></i> Voltar ao início
                </a>
                <button type="button" onclick="history.back()" class="inline-flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left text-xs"></i> Voltar
                </button>
            </div>
        </div>
    </div>
</body>
</html>