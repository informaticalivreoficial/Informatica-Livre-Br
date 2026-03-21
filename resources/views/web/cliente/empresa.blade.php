@extends('web.cliente.layouts.app')

@section('title', 'Minha Empresa - ' . $company->alias_name)

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Minha Empresa</h1>
        <p class="text-gray-500 mt-1">Seus dados cadastrados.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Logo --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 text-center">
            <img
                src="{{ $company->getlogo() }}"
                alt="{{ $company->alias_name }}"
                class="h-24 object-contain mx-auto mb-4"
            >
            <h2 class="text-xl font-bold text-gray-800">{{ $company->alias_name }}</h2>
            <p class="text-gray-500 text-sm">{{ $company->social_name }}</p>
            @if($company->document_company)
                <p class="text-gray-400 text-xs mt-2">CNPJ: {{ $company->document_company }}</p>
            @endif
            <span class="inline-block mt-3 px-3 py-1 rounded-full text-xs font-medium
                {{ $company->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $company->status ? 'Ativo' : 'Inativo' }}
            </span>
        </div>

        {{-- Dados --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Contato --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-address-book text-teal-500 mr-2"></i> Contato
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if($company->email)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">E-mail</p>
                            <p class="text-gray-700">{{ $company->email }}</p>
                        </div>
                    @endif
                    @if($company->phone)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Telefone</p>
                            <p class="text-gray-700">{{ $company->phone }}</p>
                        </div>
                    @endif
                    @if($company->cell_phone)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Celular</p>
                            <p class="text-gray-700">{{ $company->cell_phone }}</p>
                        </div>
                    @endif
                    @if($company->whatsapp)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">WhatsApp</p>
                            <p class="text-gray-700">{{ $company->whatsapp }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Responsável --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-user text-teal-500 mr-2"></i> Responsável
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    @if($company->responsable_name)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Nome</p>
                            <p class="text-gray-700">{{ $company->responsable_name }}</p>
                        </div>
                    @endif
                    @if($company->responsable_email)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">E-mail</p>
                            <p class="text-gray-700">{{ $company->responsable_email }}</p>
                        </div>
                    @endif
                    @if($company->responsable_cpf)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">CPF</p>
                            <p class="text-gray-700">{{ $company->responsable_cpf }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Endereço --}}
            @if($company->street)
                <div class="bg-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-map-marker-alt text-teal-500 mr-2"></i> Endereço
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Logradouro</p>
                            <p class="text-gray-700">
                                {{ $company->street }}, {{ $company->number }}
                                @if($company->complement) — {{ $company->complement }} @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Bairro</p>
                            <p class="text-gray-700">{{ $company->neighborhood }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Cidade/Estado</p>
                            <p class="text-gray-700">{{ $company->city }}/{{ $company->state }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">CEP</p>
                            <p class="text-gray-700">{{ $company->zipcode }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection