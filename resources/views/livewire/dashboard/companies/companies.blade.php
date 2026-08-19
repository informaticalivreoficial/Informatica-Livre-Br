<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-industry mr-2"></i> Empresas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Empresas</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-industry text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ativas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['active'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Inativas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['inactive'] }}</p>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por nome ou email..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                href="{{ route('companies.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Empresa
            </a>
        </div>

        <div class="overflow-x-auto" x-data="{ showModal: false, imageUrl: '' }">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Logomarca</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('alias_name')">
                            Nome Fantasia <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Faturas</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Responsável</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($companies as $company)
                        <tr class="hover:bg-gray-50 transition {{ $company->status ? '' : 'bg-yellow-50' }}">
                            <td class="px-4 py-3">
                                <img
                                    src="{{ $company->getlogo() }}"
                                    alt="{{ $company->alias_name }}"
                                    title="{{ $company->alias_name }}"
                                    class="w-12 h-12 object-cover rounded-lg cursor-pointer hover:scale-105 transition-transform"
                                    @click="showModal = true; imageUrl = '{{ addslashes(url($company->getlogo())) }}'"
                                />
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700">{{ $company->alias_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $company->invoices()->count() }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 text-center">{{ $company->responsable_name }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <x-forms.switch-toggle
                                        wire:key="safe-switch-{{ $company->id }}"
                                        wire:click="toggleStatus({{ $company->id }})"
                                        :checked="$company->status"
                                        size="sm"
                                        color="green"
                                    />
                                    <button type="button"
                                            class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition"
                                            wire:click="viewCompany({{ $company->id }})"
                                            title="Visualizar">
                                        <i class="fas fa-search text-xs"></i>
                                    </button>
                                    <a href="{{ route('companies.edit', ['company' => $company->id]) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition" title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    @if (auth()->user()->isSuperAdmin())
                                        <button type="button"
                                                class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                                wire:click="setDeleteId({{ $company->id }})"
                                                title="Excluir">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhuma empresa encontrada</p>
                                <a href="{{ route('companies.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar a primeira empresa
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div x-show="showModal" x-cloak
                class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-[9999]"
                x-transition>
                <div class="relative">
                    <img :src="imageUrl" class="max-w-[70vw] max-h-[70vh] object-contain mx-auto rounded shadow-lg">
                    <button type="button" @click="showModal = false"
                            class="absolute top-2 right-2 text-white text-xl bg-black bg-opacity-50 rounded-full px-2 py-1 hover:bg-opacity-75 transition">
                        ✕
                    </button>
                </div>
            </div>
        </div>

        @if ($companies->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    @if($showCompanyModal && $companySelected)
    <div
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
        x-data
        x-init="@this.on('close-modal', () => $el.remove())"
    >
        <div class="bg-white w-[600px] rounded-lg shadow-lg p-6 relative animate-fade">

            <button
                class="absolute top-2 right-2 text-xl"
                wire:click="$set('showCompanyModal', false)">
                ✕
            </button>

            <h2 class="text-xl font-bold mb-4">Detalhes da Empresa</h2>

            <div class="space-y-2">
                <p><strong>Nome Fantasia:</strong> {{ $companySelected->alias_name }}</p>
                <p><strong>Razão Social:</strong> {{ $companySelected->social_name }}</p>
                <p><strong>CNPJ:</strong> {{ $companySelected->cnpj }}</p>
                <p><strong>Status:</strong> {{ $companySelected->status ? 'Ativa' : 'Inativa' }}</p>

                <p><strong>Pedidos:</strong> {{ $companySelected->subscriptions()->count() }}</p>
                <p><strong>Faturas:</strong> {{ $companySelected->invoices()->count() }}</p>
            </div>

            <div class="mt-4 text-right">
                <button
                    class="px-4 py-2 bg-gray-600 text-white rounded"
                    wire:click="$set('showCompanyModal', false)">
                    Fechar
                </button>
            </div>

        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal', (event) => {
            const data = event
            swal.fire({
                icon:data[0]['icon'],
                title:data[0]['title'],
                text:data[0]['text'],
            })
        })

        @this.on('delete-prompt', (event) => {
            swal.fire({
                icon: 'warning',
                title: 'Atenção',
                text: 'Você tem certeza que deseja excluir esta Empresa?',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir!',
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.dispatch('goOn-Delete')
                }
            })
        })
    });
</script>