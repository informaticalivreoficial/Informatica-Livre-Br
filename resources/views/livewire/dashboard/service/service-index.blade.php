<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-store mr-2"></i> Serviços</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Serviços</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-store text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <i class="fas fa-sync-alt text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Recorrentes</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['recurring'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-gray-100 text-gray-600 flex items-center justify-center shrink-0">
                <i class="fas fa-briefcase text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Freelas</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['one_time'] }}</p>
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
                    placeholder="Buscar por nome do serviço..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
            <a
                href="{{ route('services.create') }}"
                class="inline-flex items-center justify-center bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition shrink-0"
            >
                <i class="fas fa-plus mr-2"></i> Cadastrar Serviço
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide cursor-pointer" wire:click="sortBy('name')">
                            Serviço <i class="fas fa-caret-down fa-fw ml-1"></i>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoria</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Valor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Intervalo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($services as $service)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-800">{{ $service->name }}</p>
                                @if($service->description)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ Str::limit($service->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $service->category?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($service->billing_type === 'recurring')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">Recorrente</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Freela</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800">R$ {{ number_format($service->price, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $service->interval?->label() ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($service->status == true)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('services.edit', $service) }}"
                                       class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                       title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <button
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        wire:click="confirmDelete({{ $service->id }})"
                                        title="Excluir">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum serviço cadastrado</p>
                                <a href="{{ route('services.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro serviço
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($services->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>