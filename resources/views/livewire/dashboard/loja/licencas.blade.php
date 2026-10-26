<div class="pb-10">
    @section('title', $title)

    <x-ui.page-header
        title="Loja"
        subtitle="Licenças entregues após a confirmação do pagamento"
        :breadcrumbs="['Painel de Controle' => route('admin'), 'Loja' => null, 'Licenças' => null]"
    >
        <a
            href="{{ route('loja.pedidos.index') }}"
            class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
        >
            <i class="fas fa-shopping-bag text-xs"></i> Pedidos
        </a>
    </x-ui.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <x-ui.stat-card label="Licenças" :value="$stats['total']" icon="fas fa-key" tone="teal" />
        <x-ui.stat-card label="Ativas" :value="$stats['ativas']" icon="fas fa-check-circle" tone="green" />
        <x-ui.stat-card label="Aguardando instalação" :value="$stats['aguardando']" icon="fas fa-hourglass-half" tone="amber" />
        <x-ui.stat-card label="Suspensas / canceladas" :value="$stats['suspensas']" icon="fas fa-ban" tone="red" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Buscar por chave, nome ou e-mail..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>

            <select
                wire:model.live="status"
                class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            >
                <option value="">Todos os status</option>
                <option value="aguardando">Aguardando instalação</option>
                <option value="ativa">Ativa</option>
                <option value="suspensa">Suspensa</option>
                <option value="cancelada">Cancelada</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Chave</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Produto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Pedido</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($licencas as $licenca)
                        <tr class="hover:bg-gray-50 transition" wire:key="licenca-{{ $licenca->id }}">
                            <td class="px-4 py-3">
                                <div
                                    x-data="{ copied: false, copy() { navigator.clipboard.writeText('{{ $licenca->chave }}'); this.copied = true; setTimeout(() => this.copied = false, 1500) } }"
                                    class="flex items-center gap-2"
                                >
                                    <span class="font-mono text-xs text-gray-700">{{ $licenca->chave }}</span>

                                    <button @click="copy()" class="text-gray-400 hover:text-teal-600 transition" title="Copiar chave">
                                        <i class="fas" :class="copied ? 'fa-check text-green-500' : 'fa-copy'"></i>
                                    </button>
                                </div>

                                <p class="text-xs text-gray-400 mt-0.5">{{ $licenca->created_at->format('d/m/Y') }}</p>
                            </td>

                            <td class="px-4 py-3">
                                <p class="text-sm text-gray-800 truncate max-w-[200px]">{{ $licenca->nome }}</p>
                                <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $licenca->email }}</p>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $licenca->produto?->nome ?? '—' }}
                            </td>

                            <td class="px-4 py-3">
                                <a href="{{ route('loja.pedidos.index') }}" class="text-sm text-teal-600 hover:underline">
                                    {{ $licenca->pedido?->codigo ?? '—' }}
                                </a>
                            </td>

                            <td class="px-4 py-3">
                                @php
                                    $statusTone = match ($licenca->status) {
                                        'ativa' => 'bg-green-100 text-green-700',
                                        'aguardando' => 'bg-amber-100 text-amber-700',
                                        'suspensa' => 'bg-blue-100 text-blue-700',
                                        'cancelada' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusTone }}">
                                    {{ $licenca->status_label }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button
                                        wire:click="openEdit({{ $licenca->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition"
                                        title="Editar licença"
                                    >
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>

                                    <button
                                        wire:click="confirmDelete({{ $licenca->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir licença"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-14 text-center">
                                <i class="fas fa-key text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhuma licença encontrada</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($licencas->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $licencas->links() }}
            </div>
        @endif
    </div>

    <div x-data="{ open: @entangle('showModal') }" x-cloak>
        <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-transition.opacity>
            <div class="fixed inset-0 bg-black/50" @click="open = false"></div>

            <div class="relative min-h-full flex items-start justify-center p-4">
                <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-xl my-8">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-800">Editar licença</h3>

                        <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form wire:submit.prevent="save">
                        <div class="p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select
                                    wire:model="licencaStatus"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 bg-white @error('licencaStatus') border-red-400 @enderror"
                                >
                                    <option value="aguardando">Aguardando instalação</option>
                                    <option value="ativa">Ativa</option>
                                    <option value="suspensa">Suspensa</option>
                                    <option value="cancelada">Cancelada</option>
                                </select>
                                @error('licencaStatus') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL do sistema</label>
                                    <input
                                        type="url"
                                        wire:model="url_sistema"
                                        placeholder="https://sistema.cliente.com.br"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('url_sistema') border-red-400 @enderror"
                                    >
                                    @error('url_sistema') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">URL do painel</label>
                                    <input
                                        type="url"
                                        wire:model="url_painel"
                                        placeholder="https://sistema.cliente.com.br/admin"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('url_painel') border-red-400 @enderror"
                                    >
                                    @error('url_painel') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Credenciais de acesso</label>
                                <textarea
                                    wire:model="credenciais"
                                    rows="3"
                                    placeholder="Usuário e senha entregues ao cliente"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('credenciais') border-red-400 @enderror"
                                ></textarea>
                                @error('credenciais') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notas internas</label>
                                <textarea
                                    wire:model="notas"
                                    rows="3"
                                    placeholder="Observações sobre instalação, suporte..."
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400 @error('notas') border-red-400 @enderror"
                                ></textarea>
                                @error('notas') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2">
                            <button
                                type="button"
                                @click="open = false"
                                class="px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100 transition"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                wire:loading.attr="disabled"
                                class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition disabled:opacity-60"
                            >
                                <i class="fas fa-save"></i>
                                <span wire:loading.remove wire:target="save">Salvar licença</span>
                                <span wire:loading wire:target="save">Salvando...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
