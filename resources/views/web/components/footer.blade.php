<footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-gray-700">

            {{-- Logo e descrição --}}
            <div>
                <img src="{{ $configuracoes->getlogofooter() }}" alt="{{ $configuracoes->app_name }}" class="h-12 mb-4">
                <p class="text-sm text-gray-400 leading-relaxed">
                    {!! nl2br(e($configuracoes->information)) !!}
                </p>
                <div class="flex gap-4 mt-4">
                    @if ($configuracoes->facebook)
                        <a target="_blank" href="{{ $configuracoes->facebook }}" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if ($configuracoes->twitter)
                        <a target="_blank" href="{{ $configuracoes->twitter }}" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-twitter"></i></a>
                    @endif
                    @if ($configuracoes->youtube)
                        <a target="_blank" href="{{ $configuracoes->youtube }}" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if ($configuracoes->instagram)
                        <a target="_blank" href="{{ $configuracoes->instagram }}" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if ($configuracoes->linkedin)
                        <a target="_blank" href="{{ $configuracoes->linkedin }}" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                    
                    @if ($configuracoes->whatsapp)
                        <a 
                            target="_blank" 
                            class="text-gray-400 hover:text-teal-400 transition cursor-pointer"
                            onclick="shareWhatsApp(event)"
                        >
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    @endif                    
                </div>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Links Rápidos</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('web.home') }}" class="hover:text-teal-400 transition">Início</a></li>
                    <li><a href="{{ route('web.portifolio') }}" class="hover:text-teal-400 transition">Nossos Trabalhos</a></li>
                    <li><a href="{{ route('web.blog.artigos') }}" class="hover:text-teal-400 transition">Blog</a></li>
                    <li><a href="{{ route('web.contact') }}" class="hover:text-teal-400 transition">Atendimento</a></li>
                    <li><a href="{{ route('web.terms') }}" class="hover:text-teal-400 transition">Termos e Condições</a></li>
                    <li><a @click="openModal()" class="hover:text-teal-400 transition cursor-pointer">Preferências de cookies</a></li>
                </ul>
            </div>

            {{-- Contato --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Atendimento</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-teal-400"></i>
                        {{ $configuracoes->city }}/{{ $configuracoes->state }}
                    </li>
                    @if ($configuracoes->cell_phone)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone text-teal-400"></i>
                            <a href="tel:+{{ $configuracoes->cell_phone }}" class="hover:text-teal-400 transition">{{ $configuracoes->cell_phone }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->email)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope text-teal-400"></i>
                            <a href="mailto:{{ $configuracoes->email }}" class="hover:text-teal-400 transition">{{ $configuracoes->email }}</a>
                        </li>
                    @endif                    
                    @if ($configuracoes->additional_email)
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope text-teal-400"></i>
                            <a href="mailto:{{ $configuracoes->additional_email }}" class="hover:text-teal-400 transition">{{ $configuracoes->additional_email }}</a>
                        </li>
                    @endif                    
                </ul>
            </div>
        </div>

        <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-2 text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ $configuracoes->app_name }}. Todos os direitos reservados.</p>
            <a href="{{ route('web.privacy') }}" class="hover:text-teal-400 transition">Política de Privacidade</a>
            <span class="text-xs p-2">Feito com 🖤 por {{env('DESENVOLVEDOR')}}</span>
        </div>
    </div>
</footer>