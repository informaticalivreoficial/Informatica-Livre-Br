@props(['title' => '', 'subtitle' => null, 'breadcrumbs' => []])

<div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">{{ $title }}</h1>

        @if ($subtitle)
            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="flex items-center gap-2">
        {{ $slot }}
    </div>
</div>

@if (! empty($breadcrumbs))
    <nav class="flex flex-wrap items-center gap-1.5 text-xs text-gray-400 mb-4">
        @foreach ($breadcrumbs as $label => $url)
            @if ($url)
                <a href="{{ $url }}" class="hover:text-teal-600 transition">{{ $label }}</a>
                <i class="fas fa-chevron-right text-[8px]"></i>
            @else
                <span class="text-gray-600 font-medium">{{ $label }}</span>
            @endif
        @endforeach
    </nav>
@endif
