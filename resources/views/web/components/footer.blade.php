<footer class="bg-gray-900 text-gray-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-10 border-b border-gray-700">

            {{-- Logo e descrição --}}
            <div>
                <img src="{{ asset('storage/configuracoes/logomarca-informatica-livre.png') }}" alt="Informática Livre" class="h-12 mb-4">
                <p class="text-sm text-gray-400 leading-relaxed">
                    Desenvolvimento de sites, sistemas e soluções digitais para empresas em Ubatuba/SP e região.
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-gray-400 hover:text-teal-400 transition"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://api.whatsapp.com/send?phone=5512991385030" target="_blank" class="text-gray-400 hover:text-teal-400 transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- Links --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Links Rápidos</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('web.home') }}" class="hover:text-teal-400 transition">Home</a></li>
                    <li><a href="{{ route('web.portifolio') }}" class="hover:text-teal-400 transition">Portfólio</a></li>
                    <li><a href="{{ route('web.blog.artigos') }}" class="hover:text-teal-400 transition">Blog</a></li>
                    <li><a href="{{ route('web.contato') }}" class="hover:text-teal-400 transition">Contato</a></li>
                </ul>
            </div>

            {{-- Contato --}}
            <div>
                <h4 class="text-white font-semibold mb-4">Contato</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-teal-400"></i>
                        Ubatuba/SP
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-teal-400"></i>
                        <a href="tel:+5512991385030" class="hover:text-teal-400 transition">(12) 99138-5030</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-teal-400"></i>
                        <a href="mailto:suporte@informaticalivre.com.br" class="hover:text-teal-400 transition">suporte@informaticalivre.com.br</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-2 text-sm text-gray-500">
            <p>Informática Livre © {{ date('Y') }} — Todos os direitos reservados</p>
            <a href="#" class="hover:text-teal-400 transition">Política de Privacidade</a>
        </div>
    </div>
</footer>