@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-center space-x-1 mt-6">
        {{-- Botão Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1 text-md font-medium text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="px-3 py-1 text-md font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 transition">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Números das páginas --}}
        @foreach ($elements as $element)
            {{-- "..." separador --}}
            @if (is_string($element))
                <span class="px-3 py-1 text-md font-medium text-gray-500">{{ $element }}</span>
            @endif

            {{-- Links das páginas --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1 text-md font-semibold text-white bg-teal-600 rounded-md">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1 text-md font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-teal-600 hover:text-white transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Botão Próximo --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="px-3 py-1 text-md font-medium text-white bg-teal-600 rounded-md hover:bg-teal-700 transition">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="px-3 py-1 text-md font-medium text-gray-400 bg-gray-200 rounded-md cursor-not-allowed">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </nav>
@endif