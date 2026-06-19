@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
])

<div class="w-full lg:w-1/4 px-2">

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex items-center justify-between hover:shadow-md transition">

        {{-- Conteúdo --}}
        <div>
            <p class="text-sm text-slate-500">
                {{ $title }}
            </p>

            <h3 class="text-2xl font-bold text-slate-800 mt-1">
                {{ $value }}
            </h3>
        </div>

        {{-- Ícone --}}
        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-{{ $color }}-50 text-{{ $color }}-600">
            <i class="{{ $icon }} text-xl"></i>
        </div>

    </div>

</div>