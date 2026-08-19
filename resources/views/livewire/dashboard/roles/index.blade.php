<div>
    @section('title', $title)
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-user-shield mr-2"></i> Cargos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin') }}">Painel de Controle</a></li>
                        <li class="breadcrumb-item active">Cargos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                <i class="fas fa-user-shield text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total de Cargos</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['total'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <i class="fas fa-key text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total de Permissões</p>
                <p class="text-xl font-bold text-gray-800 leading-tight">{{ $stats['permissions'] }}</p>
            </div>
        </div>

    </div>

    {{-- Formulário --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas {{ $isEdit ? 'fa-pen' : 'fa-plus' }} text-teal-600"></i>
                {{ $isEdit ? 'Editar Cargo' : 'Novo Cargo' }}
            </h2>
            @if($isEdit)
                <button wire:click="resetForm" class="text-xs text-gray-400 hover:text-gray-600 transition">
                    <i class="fas fa-times mr-1"></i> Cancelar edição
                </button>
            @endif
        </div>

        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'save' }}" class="p-5 space-y-5">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Nome do Cargo</label>
                <input
                    type="text"
                    wire:model="name"
                    placeholder="Ex.: Administrador, Editor..."
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Permissões</label>

                @php
                    $grouped = $permissions->groupBy(function ($p) {
                        return explode('.', $p->name)[0] ?? 'geral';
                    });
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($grouped as $module => $modulePermissions)
                        <div class="bg-gray-50 rounded-xl border border-gray-100 p-4">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <i class="fas fa-folder text-teal-500"></i>
                                {{ ucfirst($module) }}
                            </p>
                            <div class="space-y-2">
                                @foreach($modulePermissions as $permission)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input
                                            type="checkbox"
                                            wire:model="selectedPermissions"
                                            value="{{ $permission->name }}"
                                            class="w-4 h-4 rounded border-gray-300 text-teal-600 focus:ring-teal-400"
                                        >
                                        <span class="text-sm text-gray-600 group-hover:text-gray-800 transition">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3 pt-1">
                <button type="submit" class="inline-flex items-center gap-2 bg-teal-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition">
                    <i class="fas {{ $isEdit ? 'fa-check' : 'fa-plus' }} text-xs"></i>
                    {{ $isEdit ? 'Atualizar Cargo' : 'Criar Cargo' }}
                </button>
                @if($isEdit)
                    <button type="button" wire:click="resetForm" class="inline-flex items-center gap-2 bg-gray-100 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        <i class="fas fa-times text-xs"></i> Cancelar
                    </button>
                @endif
            </div>
        </form>
    </div>

    {{-- Listagem --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live="search"
                    placeholder="Buscar por nome do cargo..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cargo</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Permissões</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($roles as $role)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                                        <i class="fas fa-user-shield text-sm"></i>
                                    </span>
                                    <span class="text-sm font-medium text-gray-800">{{ $role->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1.5 max-w-md">
                                    @forelse($role->permissions->take(4) as $permission)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700">{{ $permission->name }}</span>
                                    @empty
                                        <span class="text-xs text-gray-400">Sem permissões</span>
                                    @endforelse
                                    @if($role->permissions->count() > 4)
                                        <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500">+{{ $role->permissions->count() - 4 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        wire:click="edit({{ $role->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <button
                                        wire:click="confirmDelete({{ $role->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-12 text-center">
                                <i class="fas fa-inbox text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum cargo encontrado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
</div>