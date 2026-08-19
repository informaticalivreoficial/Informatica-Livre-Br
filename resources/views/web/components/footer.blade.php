<footer class="bg-gray-900 text-gray-300 pt-16 pb-8 relative overflow-hidden">
    {{-- Glow decorativo --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 bg-teal-600/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 relative">

        {{-- Newsletter --}}
        <div class="bg-gradient-to-r from-teal-700 to-teal-500 rounded-2xl p-8 md:p-10 mb-14 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-teal-900/20">
            <div class="text-center md:text-left">
                <h3 class="text-white text-2xl font-bold mb-1">Fique por dentro das novidades</h3>
                <p class="text-teal-100 text-sm">Receba dicas de tecnologia e novidades do nosso blog.</p>
            </div>
            <form x-data="{ email: '', sent: false }" @submit.prevent="sent = true" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                <input
                    type="email"
                    x-model="email"
                    required
                    placeholder="Seu melhor e-mail"
                    class="flex-1 md:w-72 bg-white/10 border border-white/20 text-white placeholder-teal-200/70 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-white/50"
                >
                <button type="submit" class="bg-white text-teal-700 px-6 py-3 rounded-lg font-semibold text-sm hover:bg-teal-50 transition shadow-lg">
                    <span x-show="!sent">Assinar</span>
                    <span x-show="sent" x-cloak><i class="fas fa-check mr-1"></i> Inscrito!</span>
                </button>
            </form>
        </div>

        {{-- Colunas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-gray-700">

            {{-- Logo e descrição --}}
            <div class="lg:col-span-1">
                <img src="{{ $configuracoes->getlogofooter() }}" alt="{{ $configuracoes->app_name }}" class="h-16 mb-4">
                <p class="text-sm text-gray-400 leading-relaxed">
                    {!! nl2br(e($configuracoes->information)) !!}
                </p>
                <div class="flex gap-3 mt-5">
                    @if ($configuracoes->facebook)
                        <a target="_blank" href="{{ $configuracoes->facebook }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1"><i class="fab fa-facebook-f text-sm"></i></a>
                    @endif
                    @if ($configuracoes->twitter)
                        <a target="_blank" href="{{ $configuracoes->twitter }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1"><i class="fab fa-twitter text-sm"></i></a>
                    @endif
                    @if ($configuracoes->youtube)
                        <a target="_blank" href="{{ $configuracoes->youtube }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1"><i class="fab fa-youtube text-sm"></i></a>
                    @endif
                    @if ($configuracoes->instagram)
                        <a target="_blank" href="{{ $configuracoes->instagram }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1"><i class="fab fa-instagram text-sm"></i></a>
                    @endif
                    @if ($configuracoes->linkedin)
                        <a target="_blank" href="{{ $configuracoes->linkedin }}" class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1"><i class="fab fa-linkedin-in text-sm"></i></a>
                    @endif

                    @if ($configuracoes->whatsapp)
                        <a
                            target="_blank"
                            class="w-9 h-9 rounded-lg bg-gray-800 hover:bg-teal-600 text-gray-400 hover:text-white flex items-center justify-center transition hover:-translate-y-1 cursor-pointer"
                            onclick="shareWhatsApp(event)"
                        >
                            <i class="fab fa-whatsapp text-sm"></i>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-teal-500 rounded-full inline-block"></span>
                    Links Rápidos
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('web.home') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Início</a></li>
                    <li><a href="{{ route('web.portifolio') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Nossos Trabalhos</a></li>
                    <li><a href="{{ route('web.blog.artigos') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Blog</a></li>
                    <li><a href="{{ route('web.produtos') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Sistemas</a></li>
                    <li><a href="{{ route('web.contact') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Atendimento</a></li>
                    <li><a href="{{ route('web.terms') }}" class="hover:text-teal-400 transition flex items-center gap-2 group"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Termos e Condições</a></li>
                    <li><a @click="openModal()" class="hover:text-teal-400 transition flex items-center gap-2 group cursor-pointer"><i class="fas fa-chevron-right text-[10px] text-teal-500 group-hover:translate-x-1 transition"></i>Preferências de cookies</a></li>
                </ul>
            </div>

            {{-- Serviços --}}
            <div>
                <h4 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-teal-500 rounded-full inline-block"></span>
                    Serviços
                </h4>
                <ul class="space-y-2.5 text-sm">
                    <li class="flex items-center gap-2"><i class="fas fa-laptop-code text-teal-500 text-xs"></i>Criação de Sites</li>
                    <li class="flex items-center gap-2"><i class="fas fa-cogs text-teal-500 text-xs"></i>Sistemas Web</li>
                    <li class="flex items-center gap-2"><i class="fas fa-mobile-alt text-teal-500 text-xs"></i>Sites Responsivos</li>
                    <li class="flex items-center gap-2"><i class="fas fa-search text-teal-500 text-xs"></i>SEO & Otimização</li>
                    <li class="flex items-center gap-2"><i class="fas fa-share-alt text-teal-500 text-xs"></i>Redes Sociais</li>
                </ul>
            </div>

            {{-- Contato --}}
            <div>
                <h4 class="text-white font-semibold mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-teal-500 rounded-full inline-block"></span>
                    Atendimento
                </h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-3">
                        <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center flex-shrink-0"><i class="fas fa-map-marker-alt text-teal-400 text-xs"></i></span>
                        {{ $configuracoes->city }}/{{ $configuracoes->state }}
                    </li>
                    @if ($configuracoes->cell_phone)
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center flex-shrink-0"><i class="fas fa-phone text-teal-400 text-xs"></i></span>
                            <a href="tel:+{{ $configuracoes->cell_phone }}" class="hover:text-teal-400 transition">{{ $configuracoes->cell_phone }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->email)
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center flex-shrink-0"><i class="fas fa-envelope text-teal-400 text-xs"></i></span>
                            <a href="mailto:{{ $configuracoes->email }}" class="hover:text-teal-400 transition break-all">{{ $configuracoes->email }}</a>
                        </li>
                    @endif
                    @if ($configuracoes->additional_email)
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center flex-shrink-0"><i class="fas fa-envelope text-teal-400 text-xs"></i></span>
                            <a href="mailto:{{ $configuracoes->additional_email }}" class="hover:text-teal-400 transition break-all">{{ $configuracoes->additional_email }}</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        {{-- Bottom --}}
        <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ $configuracoes->app_name }}. Todos os direitos reservados.</p>
            <a href="{{ route('web.privacy') }}" class="hover:text-teal-400 transition">Política de Privacidade</a>
            <span class="text-xs p-2">Feito com 🖤 por {{ env('DESENVOLVEDOR') }}</span>
        </div>
    </div>
</footer>