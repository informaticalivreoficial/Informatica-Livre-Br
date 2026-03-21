<div>

    {{-- SUCESSO --}}
    @if($enviado)
        <div class="bg-teal-50 border border-teal-200 rounded-xl p-6 text-center">
            <i class="fas fa-check-circle text-teal-500 text-5xl mb-3"></i>
            <h3 class="text-xl font-bold text-teal-700 mb-2">Informações enviadas!</h3>
            <p class="text-teal-600">Obrigado, <strong>{{ \App\Helpers\Renato::getPrimeiroNome($orcamento->name) }}</strong>! Entraremos em contato em breve.</p>
        </div>

    @else

        {{-- ERRO GERAL --}}
        @if($erro)
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 text-red-700 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ $erro }}
            </div>
        @endif

        {{-- BOAS VINDAS --}}
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Olá, {{ \App\Helpers\Renato::getPrimeiroNome($orcamento->name) }}! 👋
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed">
                Seja muito bem-vindo(a)! Agradecemos por escolher nossa equipe.
                Preencha o formulário abaixo para darmos andamento ao seu orçamento.
                Suas informações estão em ambiente seguro e odiamos spam!
            </p>
        </div>

        <div class="space-y-8">

            {{-- SEÇÃO 1: Dados pessoais --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal-600 text-white text-xs font-bold mr-2">1</span>
                    Dados do responsável
                </h3>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model="nome"
                                placeholder="Seu nome completo"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('nome') border-red-400 @enderror"
                            >
                            @error('nome') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail <span class="text-red-500">*</span></label>
                            <input
                                type="email"
                                wire:model="email"
                                placeholder="seu@email.com"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('email') border-red-400 @enderror"
                            >
                            @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model="telefone"
                                placeholder="(00) 00000-0000"
                                x-mask="(99) 99999-9999"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('telefone') border-red-400 @enderror"
                            >
                            @error('telefone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CPF <span class="text-red-500">*</span></label>
                            <input
                                type="text"
                                wire:model="cpf"
                                placeholder="000.000.000-00"
                                x-mask="999.999.999-99"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('cpf') border-red-400 @enderror"
                            >
                            @error('cpf') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 2: Dados da empresa --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-1 pb-2 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal-600 text-white text-xs font-bold mr-2">2</span>
                    Dados da empresa
                </h3>
                <p class="text-xs text-gray-400 mb-4 ml-8">Pessoa física pode preencher apenas endereço e contatos.</p>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Empresa</label>
                            <input type="text" wire:model="empresa" placeholder="Razão social ou nome fantasia"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">E-mail da empresa</label>
                            <input type="email" wire:model="email_empresa" placeholder="empresa@email.com"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ</label>
                            <input type="text" wire:model="cnpj" placeholder="00.000.000/0000-00" x-mask="99.999.999/9999-99"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone fixo</label>
                            <input type="text" wire:model="telefone_fixo" placeholder="(00) 0000-0000" x-mask="(99) 9999-9999"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                            <input type="text" wire:model="whatsapp" placeholder="(00) 00000-0000" x-mask="(99) 99999-9999"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 3: Endereço --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal-600 text-white text-xs font-bold mr-2">3</span>
                    Endereço
                </h3>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                            <input
                                type="text"
                                wire:model.blur="cep"
                                wire:change="buscarCep"
                                placeholder="00.000-000"
                                x-mask="99.999-999"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                            >
                            @if($buscandoCep)
                                <div class="absolute right-3 top-9">
                                    <i class="fas fa-spinner fa-spin text-teal-400 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rua</label>
                            <input type="text" wire:model="rua" placeholder="Nome da rua"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                            <input type="text" wire:model="numero" placeholder="Nº"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                            <input type="text" wire:model="complemento" placeholder="Apto, sala..."
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                            <input type="text" wire:model="bairro" placeholder="Bairro"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                            <input type="text" wire:model="cidade" placeholder="Cidade"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-6 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                            <input type="text" wire:model="uf" placeholder="SP" maxlength="2"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 uppercase">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SEÇÃO 4: Informações adicionais --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-4 pb-2 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-teal-600 text-white text-xs font-bold mr-2">4</span>
                    Informações adicionais
                </h3>
                <textarea
                    wire:model="notas_adicionais"
                    rows="5"
                    placeholder="Outros telefones, e-mails, redes sociais, observações sobre o projeto..."
                    class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 resize-none"
                ></textarea>
            </div>

            {{-- BOTÃO --}}
            <button
                wire:click="enviar"
                wire:loading.attr="disabled"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2"
            >
                <span wire:loading.remove wire:target="enviar">
                    <i class="fas fa-paper-plane mr-2"></i> Enviar informações
                </span>
                <span wire:loading wire:target="enviar">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Enviando...
                </span>
            </button>

            <p class="text-center text-xs text-gray-400">
                <i class="fas fa-lock mr-1"></i> Seus dados estão seguros e não serão compartilhados.
            </p>

        </div>
    @endif

</div>