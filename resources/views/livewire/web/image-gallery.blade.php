<div>
    <!-- Botão com contador -->
    @if (count($images))
        <button
            wire:click="openGallery(0)"
            class="absolute bottom-4 right-4 bg-white/90 backdrop-blur-sm px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 hover:bg-white transition-colors shadow-lg"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 14 15">
                <path d="M6 1.65v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.48 1.48 0 0 1 0 4.65v-3A1.5 1.5 0 0 1 1.5.15h3c.813 0 1.5.687 1.5 1.5m0 8v3a1.5 1.5 0 0 1-1.5 1.5h-3a1.48 1.48 0 0 1-1.5-1.5v-3a1.5 1.5 0 0 1 1.5-1.5h3c.813 0 1.5.687 1.5 1.5m2-8A1.5 1.5 0 0 1 9.5.15h3c.813 0 1.5.687 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.48 1.48 0 0 1 8 4.65zm6 8v3a1.5 1.5 0 0 1-1.5 1.5h-3a1.48 1.48 0 0 1-1.5-1.5v-3a1.5 1.5 0 0 1 1.5-1.5h3c.813 0 1.5.687 1.5 1.5"></path>
            </svg>
            <span class="font-medium">+ {{ count($images) }} fotos</span>
        </button>
    @endif

    <!-- Modal -->
    @if ($open)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-data
            x-on:keydown.escape.window="$wire.close()"
            x-on:keydown.arrow-left.window="$wire.prev()"
            x-on:keydown.arrow-right.window="$wire.next()"
        >
            <!-- Backdrop -->
            <div
                class="absolute inset-0 bg-black/85 backdrop-blur-sm"
                wire:click="close"
            ></div>

            <!-- Content -->
            <div class="relative z-10 w-full max-w-5xl flex flex-col gap-3">

                <!-- Fechar + contador -->
                <div class="flex items-center justify-between text-white">
                    <span class="text-sm font-medium opacity-70">
                        {{ $current + 1 }} / {{ count($images) }}
                    </span>
                    <button wire:click="close" class="p-2 rounded-full hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Imagem principal + setas -->
                <div class="relative flex items-center justify-center gap-2">
                    <!-- Seta anterior -->
                    <button
                        wire:click="prev"
                        class="flex-shrink-0 p-2 md:p-3 rounded-full bg-black/40 hover:bg-black/60 text-white transition-colors"
                    >
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Imagem -->
                    <div class="flex-1 flex items-center justify-center min-w-0">
                        <img
                            src="{{ Storage::url($images[$current]['path']) }}"
                            alt="Foto {{ $current + 1 }}"
                            class="max-h-[60vh] md:max-h-[70vh] max-w-full w-auto h-auto object-contain rounded-xl"
                            wire:key="img-{{ $current }}"
                        />
                    </div>

                    <!-- Seta próxima -->
                    <button
                        wire:click="next"
                        class="flex-shrink-0 p-2 md:p-3 rounded-full bg-black/40 hover:bg-black/60 text-white transition-colors"
                    >
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>

                <!-- Thumbnails -->
                <div class="flex gap-2 overflow-x-auto pb-1 justify-start md:justify-center px-1">
                    @foreach ($images as $index => $image)
                        <button
                            wire:click="openGallery({{ $index }})"
                            class="flex-shrink-0 w-12 h-12 md:w-16 md:h-16 rounded-lg overflow-hidden border-2 transition-all {{ $current === $index ? 'border-white opacity-100' : 'border-transparent opacity-50 hover:opacity-80' }}"
                        >
                            <img src="{{ Storage::url($image['path']) }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>

            </div>
        </div>
    @endif
</div>