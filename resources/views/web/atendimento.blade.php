@extends('web.layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="bg-gradient-to-r from-teal-700 to-teal-500 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center text-white">
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
                    <div class="bg-white rounded-2xl shadow-sm p-8">                        
                        <livewire:web.contact-form  />
                    </div>
                </div>

                {{-- Informações --}}
                <aside class="space-y-6">

                    {{-- Contato --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Informações de Contato</h3>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-map-marker-alt text-teal-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase tracking-wide">Localização</p>
                                    <p class="text-gray-700 font-medium">
                                        {{ $configuracoes->city }}/{{ $configuracoes->state }}
                                    </p>
                                </div>
                            </li>
                            @if ($configuracoes->whatsapp)
                                <li class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fab fa-whatsapp text-teal-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">WhatsApp</p>
                                        <a  onclick="shareWhatsApp(event)"
                                            target="_blank"
                                            class="text-gray-700 font-medium hover:text-teal-600 transition cursor-pointer">
                                            {{ $configuracoes->whatsapp }}
                                        </a>
                                    </div>
                                </li>
                            @endif    
                            @if ($configuracoes->email)
                                <li class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope text-teal-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wide">E-mail</p>
                                        <a href="mailto:{{ $configuracoes->email }}"
                                            class="text-gray-700 font-medium hover:text-teal-600 transition">
                                            {{ $configuracoes->email }}
                                        </a>
                                        @if ($configuracoes->additional_email)
                                            <br>
                                            <a href="mailto:{{ $configuracoes->additional_email }}"
                                                class="text-gray-700 font-medium hover:text-teal-600 transition">
                                                {{ $configuracoes->additional_email }}
                                            </a>
                                        @endif
                                    </div>
                                </li>
                            @endif 
                        </ul>
                    </div>

                    {{-- Redes Sociais --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Redes Sociais</h3>
                        <div class="flex gap-3">
                            @if ($configuracoes->facebook)
                                <a href="{{ $configuracoes->facebook }}" target="_blank"
                                    class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            @endif
                            @if ($configuracoes->twitter)
                                <a href="{{ $configuracoes->twitter }}" target="_blank"
                                    class="w-10 h-10 bg-blue-400 text-white rounded-lg flex items-center justify-center hover:bg-blue-500 transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            @endif
                            @if ($configuracoes->youtube)
                                <a href="{{ $configuracoes->youtube }}" target="_blank"
                                    class="w-10 h-10 bg-red-500 text-white rounded-lg flex items-center justify-center hover:bg-red-600 transition">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            @endif
                            @if ($configuracoes->instagram)
                                <a href="{{ $configuracoes->instagram }}" target="_blank"
                                    class="w-10 h-10 bg-pink-500 text-white rounded-lg flex items-center justify-center hover:bg-pink-600 transition">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            @endif
                            @if ($configuracoes->linkedin)
                                <a href="{{ $configuracoes->linkedin }}" target="_blank"
                                    class="w-10 h-10 bg-blue-700 text-white rounded-lg flex items-center justify-center hover:bg-blue-800 transition">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            @endif
                            @if ($configuracoes->whatsapp)
                                <a onclick="shareWhatsApp(event)" target="_blank"
                                    class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center hover:bg-green-600 transition cursor-pointer">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            @endif  
                        </div>
                    </div>

                    {{-- Horário --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Horário de Atendimento</h3>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex justify-between">
                                <span>Segunda - Sexta</span>
                                <span class="font-medium text-gray-800">08:00 - 18:00</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Sábado</span>
                                <span class="font-medium text-gray-800">08:00 - 12:00</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Domingo</span>
                                <span class="font-medium text-red-500">Fechado</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

@endsection