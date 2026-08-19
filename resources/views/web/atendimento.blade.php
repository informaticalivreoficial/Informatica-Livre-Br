@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-gradient-to-br from-teal-800 via-teal-700 to-blue-800 py-20 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 text-center text-white relative">
            <span class="inline-block bg-white/10 text-teal-100 text-xs font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider mb-4 backdrop-blur">Atendimento</span>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4">Fale Conosco</h1>
            <p class="text-teal-100 text-lg max-w-xl mx-auto">Tire suas dúvidas, respondemos rapidamente!</p>
        </div>
    </section>

    {{-- CONTEÚDO --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Formulário --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100" x-data="reveal" :class="shown ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'" class="transition-all duration-700">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                            <span class="w-1.5 h-6 bg-teal-500 rounded-full inline-block"></span>
                            Envie sua mensagem
                        </h2>
                        <livewire:web.contact-form />
                    </div>
                </div>

                {{-- Informações --}}
                <aside class="space-y-6">

                    {{-- Contato --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-address-card text-teal-600"></i>
                            Informações de Contato
                        </h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3 group">
                                <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:scale-110 transition-all duration-300">
                                    <i class="fas fa-map-marker-alt text-teal-600 group-hover:text-white transition-colors"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Localização</p>
                                    <p class="text-gray-700 font-medium">
                                        {{ $configuracoes->city }}/{{ $configuracoes->state }}
                                    </p>
                                </div>
                            </li>
                            @if ($configuracoes->whatsapp)
                                <li class="flex items-start gap-3 group">
                                    <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:scale-110 transition-all duration-300">
                                        <i class="fab fa-whatsapp text-teal-600 group-hover:text-white transition-colors"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">WhatsApp</p>
                                        <a onclick="shareWhatsApp(event)"
                                            target="_blank"
                                            class="text-gray-700 font-medium hover:text-teal-600 transition cursor-pointer">
                                            {{ $configuracoes->whatsapp }}
                                        </a>
                                    </div>
                                </li>
                            @endif
                            @if ($configuracoes->email)
                                <li class="flex items-start gap-3 group">
                                    <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:scale-110 transition-all duration-300">
                                        <i class="fas fa-envelope text-teal-600 group-hover:text-white transition-colors"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">E-mail</p>
                                        <a href="mailto:{{ $configuracoes->email }}"
                                            class="text-gray-700 font-medium hover:text-teal-600 transition break-all">
                                            {{ $configuracoes->email }}
                                        </a>
                                        @if ($configuracoes->additional_email)
                                            <br>
                                            <a href="mailto:{{ $configuracoes->additional_email }}"
                                                class="text-gray-700 font-medium hover:text-teal-600 transition break-all">
                                                {{ $configuracoes->additional_email }}
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>

                    {{-- Redes Sociais --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-share-alt text-teal-600"></i>
                            Redes Sociais
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            @if ($configuracoes->facebook)
                                <a href="{{ $configuracoes->facebook }}" target="_blank"
                                    class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition hover:-translate-y-1 shadow-md">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if ($configuracoes->twitter)
                                <a href="{{ $configuracoes->twitter }}" target="_blank"
                                    class="w-10 h-10 bg-blue-400 text-white rounded-lg flex items-center justify-center hover:bg-blue-500 transition hover:-translate-y-1 shadow-md">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if ($configuracoes->youtube)
                                <a href="{{ $configuracoes->youtube }}" target="_blank"
                                    class="w-10 h-10 bg-red-500 text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition hover:-translate-y-1 shadow-md">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            @if ($configuracoes->instagram)
                                <a href="{{ $configuracoes->instagram }}" target="_blank"
                                    class="w-10 h-10 bg-pink-500 text-white rounded-lg flex items-center justify-center hover:bg-pink-600 transition hover:-translate-y-1 shadow-md">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if ($configuracoes->linkedin)
                                <a href="{{ $configuracoes->linkedin }}" target="_blank"
                                    class="w-10 h-10 bg-blue-700 text-white rounded-lg flex items-center justify-center hover:bg-blue-800 transition hover:-translate-y-1 shadow-md">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if ($configuracoes->whatsapp)
                                <a onclick="shareWhatsApp(event)" target="_blank"
                                    class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition hover:-translate-y-1 shadow-md cursor-pointer">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Horário --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <i class="fas fa-clock text-teal-600"></i>
                            Horário de Atendimento
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50 transition">
                                <span>Segunda - Sexta</span>
                                <span class="font-medium text-gray-800">08:00 - 18:00</span>
                            </li>
                            <li class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50 transition">
                                <span>Sábado</span>
                                <span class="font-medium text-gray-800">08:00 - 12:00</span>
                            </li>
                            <li class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-50 transition">
                                <span>Domingo</span>
                                <span class="font-medium text-red-500"><i class="fas fa-times-circle mr-1"></i>Fechado</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

@endsection