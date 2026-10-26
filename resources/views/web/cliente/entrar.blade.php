<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Painel - Informática Livre</title>
    <link rel="stylesheet" href="{{ asset('theme/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-br from-teal-700 to-teal-500 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <img src="{{ $configuracoes?->getlogo() ?? asset('theme/images/image.jpg') }}" alt="Informática Livre" class="h-14 mx-auto mb-4">
            <h1 class="text-2xl font-bold text-gray-800">Painel do Cliente</h1>
            <p class="text-gray-500 text-sm mt-1">Informe seu e-mail para receber o link de acesso</p>
        </div>

        @if(session('success'))
            <div class="bg-teal-50 border border-teal-200 text-teal-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('cliente.enviar-link') }}">
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="seu@email.com"
                    class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('email') border-red-400 @enderror"
                >
                @error('email')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Enviar Link de Acesso
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-6">
            O link é válido por 15 minutos e pode ser usado apenas uma vez.
        </p>
    </div>

</body>
</html>