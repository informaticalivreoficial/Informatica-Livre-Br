@php
    if (!empty(auth()->user()->avatar) && Storage::exists(auth()->user()->avatar)) {
        $cover = Storage::url(auth()->user()->avatar);
    } else {
        if (auth()->user()->gender == 'masculino') {
            $cover = asset('theme/images/avatar5.png');
        } elseif (auth()->user()->gender == 'feminino') {
            $cover = asset('theme/images/avatar3.png');
        } else {
            $cover = asset('theme/images/image.jpg');
        }
    }
@endphp

<nav class="bg-white border-b border-slate-200 h-16 px-6 flex items-center justify-between shadow-sm">

    {{-- LADO ESQUERDO --}}
    <div class="flex items-center gap-4">

        {{-- Toggle Sidebar --}}
        <button
            type="button"
            data-widget="pushmenu"
            class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 transition"
        >
            <i class="fas fa-bars text-slate-600"></i>
        </button>

    </div>

    {{-- LADO DIREITO --}}
    <div class="flex items-center gap-2">

        {{-- Ver Site --}}
        <a
            href="{{ route('web.home') }}"
            target="_blank"
            title="Ver Site"
            class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 transition"
        >
            <i class="fas fa-desktop text-slate-600"></i>
        </a>

        {{-- Notificações --}}
        <livewire:components.notifications-dropdown />

        {{-- Fullscreen --}}
        <button
            type="button"
            data-widget="fullscreen"
            class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 transition"
        >
            <i class="fas fa-expand-arrows-alt text-slate-600"></i>
        </button>

        {{-- Usuário --}}
        <div
            x-data="{ open: false }"
            class="relative ml-2"
        >

            {{-- Avatar --}}
            <button
                @click="open = !open"
                class="focus:outline-none"
            >
                <img
                    src="{{ $cover }}"
                    alt="{{ auth()->user()->name }}"
                    class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-200 hover:ring-blue-400 transition"
                >
            </button>

            {{-- Dropdown --}}
            <div
                x-show="open"
                @click.outside="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden z-50"
                style="display:none;"
            >

                {{-- Header --}}
                <div class="px-3 py-4 bg-slate-50 border-b border-slate-100">

                    <div class="flex items-center gap-3">

                        <img
                            src="{{ $cover }}"
                            alt="{{ auth()->user()->name }}"
                            class="w-12 h-12 rounded-full object-cover"
                        >

                        <div>
                            <p class="font-semibold text-slate-800">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-slate-500">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Menu --}}
                <div class="py-2">

                    {{-- Perfil --}}
                    <a
                        href="{{ route('users.edit', auth()->user()->id) }}"
                        class="flex items-center gap-3 px-5 py-3 text-slate-700 hover:bg-slate-50 transition"
                    >
                        <i class="fas fa-user w-5 text-slate-400"></i>
                        <span>Meu Perfil</span>
                    </a>

                    {{-- Financeiro --}}
                    <a
                        href="#"
                        class="flex items-center gap-3 px-5 py-3 text-slate-700 hover:bg-slate-50 transition"
                    >
                        <i class="fas fa-file-invoice w-5 text-slate-400"></i>
                        <span>Financeiro</span>
                    </a>

                    {{-- Suporte --}}
                    <button
                        type="button"
                        x-on:click="$dispatch('open-support-modal'); open = false"
                        class="w-full flex items-center gap-3 px-5 py-3 text-left text-slate-700 hover:bg-slate-50 transition"
                    >
                        <i class="fas fa-life-ring w-5 text-red-500"></i>
                        <span>Ajuda & Suporte</span>
                    </button>

                </div>

                {{-- Footer --}}
                <div class="border-t border-slate-100 p-2">

                    @auth
                        <livewire:auth.button-logout />
                    @endauth

                </div>

            </div>

        </div>

    </div>

</nav>