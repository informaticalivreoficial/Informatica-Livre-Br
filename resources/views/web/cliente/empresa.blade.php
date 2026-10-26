@extends('web.cliente.layouts.app')

@section('title', 'Meus dados - ' . $company->alias_name)

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Meus dados</h1>
            <p class="text-gray-500 mt-1">Mantenha os dados da empresa e do responsável atualizados.</p>
        </div>

        <a href="{{ route('cliente.dashboard') }}"
            class="inline-flex items-center gap-2 border border-gray-200 bg-white text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
            <i class="fas fa-arrow-left text-xs"></i> Voltar ao painel
        </a>
    </div>

    <form method="POST" action="{{ route('cliente.empresa.atualizar') }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">
                            <i class="fas fa-building text-teal-500 mr-2"></i> Dados da empresa
                        </h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="alias_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nome fantasia *</label>
                            <input
                                id="alias_name"
                                type="text"
                                name="alias_name"
                                value="{{ old('alias_name', $company->alias_name) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('alias_name') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('alias_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="social_name" class="block text-sm font-medium text-gray-700 mb-1.5">Razão social</label>
                            <input
                                id="social_name"
                                type="text"
                                name="social_name"
                                value="{{ old('social_name', $company->social_name) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('social_name') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('social_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="document_company" class="block text-sm font-medium text-gray-700 mb-1.5">CNPJ / CPF</label>
                            <input
                                id="document_company"
                                type="text"
                                name="document_company"
                                value="{{ old('document_company', $company->document_company) }}"
                                x-data
                                x-init="IMask($el, { mask: [{ mask: '000.000.000-00' }, { mask: '00.000.000/0000-00' }] })"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('document_company') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('document_company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">
                            <i class="fas fa-address-book text-teal-500 mr-2"></i> Contato
                        </h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mail principal *</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $company->email) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('email') border-red-400 @else border-gray-200 @enderror"
                            >
                            <p class="text-xs text-gray-400 mt-1">Este e-mail é usado para acessar o painel do cliente.</p>
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Telefone</label>
                            <input
                                id="phone"
                                type="tel"
                                name="phone"
                                value="{{ old('phone', $company->phone) }}"
                                x-data
                                x-init="IMask($el, { mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }] })"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('phone') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1.5">WhatsApp</label>
                            <input
                                id="whatsapp"
                                type="tel"
                                name="whatsapp"
                                value="{{ old('whatsapp', $company->whatsapp) }}"
                                x-data
                                x-init="IMask($el, { mask: [{ mask: '(00) 0000-0000' }, { mask: '(00) 00000-0000' }] })"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('whatsapp') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">
                            <i class="fas fa-user text-teal-500 mr-2"></i> Responsável
                        </h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="responsable_name" class="block text-sm font-medium text-gray-700 mb-1.5">Nome *</label>
                            <input
                                id="responsable_name"
                                type="text"
                                name="responsable_name"
                                value="{{ old('responsable_name', $company->responsable_name) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('responsable_name') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('responsable_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="responsable_email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mail *</label>
                            <input
                                id="responsable_email"
                                type="email"
                                name="responsable_email"
                                value="{{ old('responsable_email', $company->responsable_email) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('responsable_email') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('responsable_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="responsable_cpf" class="block text-sm font-medium text-gray-700 mb-1.5">CPF</label>
                            <input
                                id="responsable_cpf"
                                type="text"
                                name="responsable_cpf"
                                value="{{ old('responsable_cpf', $company->responsable_cpf) }}"
                                x-data
                                x-init="IMask($el, { mask: '000.000.000-00' })"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('responsable_cpf') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('responsable_cpf') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="font-bold text-gray-800">
                            <i class="fas fa-map-marker-alt text-teal-500 mr-2"></i> Endereço
                        </h2>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-6 gap-5">
                        <div class="md:col-span-2">
                            <label for="zipcode" class="block text-sm font-medium text-gray-700 mb-1.5">CEP</label>
                            <input
                                id="zipcode"
                                type="text"
                                name="zipcode"
                                value="{{ old('zipcode', $company->zipcode) }}"
                                x-data
                                x-init="IMask($el, { mask: '00000-000' })"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('zipcode') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('zipcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-4">
                            <label for="street" class="block text-sm font-medium text-gray-700 mb-1.5">Logradouro</label>
                            <input
                                id="street"
                                type="text"
                                name="street"
                                value="{{ old('street', $company->street) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('street') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('street') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="number" class="block text-sm font-medium text-gray-700 mb-1.5">Número</label>
                            <input
                                id="number"
                                type="text"
                                name="number"
                                value="{{ old('number', $company->number) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('number') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-4">
                            <label for="complement" class="block text-sm font-medium text-gray-700 mb-1.5">Complemento</label>
                            <input
                                id="complement"
                                type="text"
                                name="complement"
                                value="{{ old('complement', $company->complement) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('complement') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('complement') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="neighborhood" class="block text-sm font-medium text-gray-700 mb-1.5">Bairro</label>
                            <input
                                id="neighborhood"
                                type="text"
                                name="neighborhood"
                                value="{{ old('neighborhood', $company->neighborhood) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('neighborhood') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('neighborhood') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1.5">Cidade</label>
                            <input
                                id="city"
                                type="text"
                                name="city"
                                value="{{ old('city', $company->city) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 @error('city') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1.5">UF</label>
                            <input
                                id="state"
                                type="text"
                                name="state"
                                maxlength="2"
                                value="{{ old('state', $company->state) }}"
                                class="w-full rounded-xl border px-4 py-3 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-teal-500 @error('state') border-red-400 @else border-gray-200 @enderror"
                            >
                            @error('state') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3">
                    <a href="{{ route('cliente.dashboard') }}"
                        class="inline-flex items-center gap-2 border border-gray-200 bg-white text-gray-600 px-5 py-3 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                        Cancelar
                    </a>

                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition">
                        <i class="fas fa-save text-xs"></i> Salvar alterações
                    </button>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm p-6 text-center">
                    <img
                        src="{{ $company->getlogo() }}"
                        alt="{{ $company->alias_name }}"
                        class="h-24 object-contain mx-auto mb-4"
                    >
                    <h2 class="text-xl font-bold text-gray-800">{{ $company->alias_name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $company->social_name }}</p>

                    @if($company->document_company)
                        <p class="text-gray-400 text-xs mt-2">{{ $company->document_company }}</p>
                    @endif

                    <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-medium
                        {{ $company->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $company->status ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="bg-teal-50 border border-teal-100 rounded-2xl p-5 text-sm text-teal-800">
                    <p class="font-semibold mb-2">
                        <i class="fas fa-circle-info mr-1"></i> Precisa de ajuda?
                    </p>
                    <p class="text-teal-700 text-xs leading-relaxed">
                        Se precisar alterar o e-mail de acesso ou outros dados cadastrais, fale com a nossa equipe de suporte.
                    </p>
                    <a href="{{ route('web.contact') }}" class="inline-flex items-center gap-1 mt-3 text-xs font-semibold text-teal-700 hover:underline">
                        Falar com o suporte <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
            </aside>
        </div>
    </form>

@endsection
