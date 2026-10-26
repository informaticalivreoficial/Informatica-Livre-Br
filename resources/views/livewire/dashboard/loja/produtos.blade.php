<div class="pb-10">
    @section('title', $title)

    <x-ui.page-header
        title="Loja"
        subtitle="Pacotes de desenvolvimento, manutenção e hospedagem"
        :breadcrumbs="['Painel de Controle' => route('admin'), 'Loja' => null, 'Produtos' => null]"
    >
        <a
            href="{{ route('loja.produtos.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-teal-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition"
        >
            <i class="fas fa-plus"></i> Novo produto
        </a>
    </x-ui.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
        <x-ui.stat-card label="Produtos" :value="$stats['total']" icon="fas fa-boxes" tone="teal" />
        <x-ui.stat-card label="Ativos" :value="$stats['ativos']" icon="fas fa-check-circle" tone="green" />
        <x-ui.stat-card label="Em destaque" :value="$stats['destaques']" icon="fas fa-star" tone="amber" />
        <x-ui.stat-card label="Planos" :value="$stats['planos']" icon="fas fa-layer-group" tone="blue" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input
                    type="text"
                    wire:model.live.debounce.400ms="search"
                    placeholder="Buscar por nome, slug ou chamada..."
                    class="w-full border border-gray-200 rounded-lg pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
                >
            </div>

            <select
                wire:model.live="status"
                class="border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-teal-400"
            >
                <option value="">Todos os status</option>
                <option value="1">Ativos</option>
                <option value="0">Inativos</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Produto</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Planos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">A partir de</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Pedidos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Destaque</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Ações</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">
                    @forelse($produtos as $produto)
                        <tr class="hover:bg-gray-50 transition" wire:key="produto-{{ $produto->id }}">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($produto->cover)
                                            <img src="{{ $produto->cover->url }}" alt="{{ $produto->nome }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-300"></i>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $produto->nome }}</p>
                                        <p class="text-xs text-gray-400 truncate">
                                            /{{ $produto->slug }}
                                            @if($produto->headline)
                                                · {{ $produto->headline }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        <i class="fas fa-layer-group text-[10px]"></i> {{ $produto->planos->count() }}
                                    </span>

                                    @if($produto->tem_plano_recorrente)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                            <i class="fas fa-sync-alt text-[10px]"></i> recorrente
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $produto->menor_preco ? 'R$ ' . number_format($produto->menor_preco, 2, ',', '.') : '—' }}
                            </td>

                            <td class="px-4 py-3 text-center text-sm text-gray-500">
                                {{ $produto->pedido_itens_count }}
                            </td>

                            <td class="px-4 py-3">
                                <button wire:click="toggleStatus({{ $produto->id }})" title="Ativar/desativar">
                                    @if($produto->status)
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Ativo</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inativo</span>
                                    @endif
                                </button>
                            </td>

                            <td class="px-4 py-3 text-center">
                                <button wire:click="toggleDestaque({{ $produto->id }})" title="Destacar/remover destaque">
                                    <i class="fas fa-star {{ $produto->destaque ? 'text-amber-400' : 'text-gray-200 hover:text-amber-300' }} transition"></i>
                                </button>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a
                                        href="{{ route('web.produto', $produto->slug) }}"
                                        target="_blank"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition"
                                        title="Ver no site"
                                    >
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>

                                    <a
                                        href="{{ route('loja.produtos.edit', $produto) }}"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-teal-50 text-teal-600 hover:bg-teal-600 hover:text-white transition"
                                        title="Editar produto e planos"
                                    >
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>

                                    <button
                                        wire:click="confirmDelete({{ $produto->id }})"
                                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
                                        title="Excluir"
                                    >
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-14 text-center">
                                <i class="fas fa-box-open text-3xl text-gray-300 mb-3"></i>
                                <p class="text-sm text-gray-400">Nenhum produto cadastrado</p>
                                <a href="{{ route('loja.produtos.create') }}" class="inline-block mt-3 text-sm text-teal-600 hover:text-teal-700 font-medium">
                                    <i class="fas fa-plus mr-1"></i> Cadastrar o primeiro produto
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($produtos->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $produtos->links() }}
            </div>
        @endif
    </div>
</div>
