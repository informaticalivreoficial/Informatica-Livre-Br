@props(['label', 'value', 'icon' => 'fas fa-chart-simple', 'tone' => 'teal', 'hint' => null])

@php
    $tones = [
        'teal' => 'bg-teal-50 text-teal-600',
        'green' => 'bg-green-50 text-green-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-red-50 text-red-600',
        'gray' => 'bg-gray-100 text-gray-500',
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
    <div class="w-11 h-11 rounded-lg {{ $tones[$tone] ?? $tones['teal'] }} flex items-center justify-center shrink-0">
        <i class="{{ $icon }} text-lg"></i>
    </div>

    <div class="min-w-0">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $label }}</p>
        <p class="text-xl font-bold text-gray-800 leading-tight truncate">{{ $value }}</p>

        @if ($hint)
            <p class="text-xs text-gray-400 mt-0.5">{{ $hint }}</p>
        @endif
    </div>
</div>
